<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Vipx\BotDetect\BotDetector;
use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataCollection;

class BotDetectorTest extends TestCase
{

    private $metadataCollection;
    private $loader;

    public function testInvalidOptions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $detector = $this->createDetector();

        $detector->setOptions(
            [
                'invalid_options' => 'value',
            ]
        );
    }

    public function testValidOptions(): void
    {
        $options = [
            'debug' => true,
        ];

        $detector = $this->createDetector();
        $detector->setOptions($options);
        $options = $detector->getOptions();

        $this->assertArrayHasKey('debug', $options);
        $this->assertTrue($options['debug']);
    }

    public function testMetadata(): void
    {
        $detector = $this->createDetector();
        $metadataCollection = $this->getMetadataCollection();

        $this->assertEquals($metadataCollection->getMetadatas(), $detector->getMetadatas());
    }

    public function testCacheOptions(): void
    {
        $cacheFile = tempnam(sys_get_temp_dir(), 'vipx_bot_detect_test_metadata_');

        $options = [
            'debug' => true,
            'cache_dir' => dirname($cacheFile),
            'metadata_cache_file' => basename($cacheFile),
        ];

        $metadataCollection = $this->getMetadataCollection();

        $metadataCollection->expects($this->any())
            ->method('getResources')
            ->willReturn(
                [
                    new FileResource($cacheFile),
                ]
            );

        $detector = $this->createDetector();
        $detector->setOptions($options);

        try {
            unlink($cacheFile);

            $this->assertNotNull($detector->detect('Googlebot', '127.0.0.1'));
            $this->assertTrue(\file_exists($cacheFile));

            $cachedDetector = $this->createDetector();
            $cachedDetector->setOptions($options);

            $this->assertNotNull($cachedDetector->detect('Googlebot', '127.0.0.1'));
        } finally {
            $files = [$cacheFile, $cacheFile.'.meta'];

            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    public function testDetection(): void
    {
        $detector = $this->createDetector();

        $this->assertInstanceOf(
            'Vipx\BotDetect\Metadata\MetadataInterface',
            $detector->detect('Googlebot', '127.0.0.1')
        );
        $this->assertNull($detector->detect('VipxBot', '127.0.0.1'));
    }

    public function testUserAgentTrim(): void
    {
        $detector = $this->createDetector();
        $this->assertInstanceOf(
            'Vipx\BotDetect\Metadata\MetadataInterface',
            $detector->detect(' Googlebot ', '127.0.0.1')
        );
    }

    private function createDetector(): BotDetector
    {
        return new BotDetector($this->getLoader(), '/my/vipx/bot/file.yml');
    }

    private function getLoader()
    {
        if (null === $this->loader) {
            $loader = $this->getMockBuilder(LoaderInterface::class)
                ->getMock();

            $loader->expects($this->any())
                ->method('load')
                ->will($this->returnValue($this->getMetadataCollection()));

            $this->loader = $loader;
        }

        return $this->loader;
    }

    private function getMetadataCollection(): MetadataCollection
    {
        if (null === $this->metadataCollection) {
            $googleBot = new Metadata('Googlebot', 'Googlebot', '127.0.0.1');

            $metadatas = [
                $googleBot,
            ];

            $this->metadataCollection = $this->getMockBuilder(MetadataCollection::class)
                ->getMock();

            $this->metadataCollection->expects($this->any())
                ->method('getIterator')
                ->will(
                    $this->returnCallback(
                        function () use ($metadatas) {
                            return new \ArrayIterator($metadatas);
                        }
                    )
                );

            $this->metadataCollection->expects($this->any())
                ->method('getMetadatas')
                ->willReturn($metadatas);
        }

        return $this->metadataCollection;
    }

}
