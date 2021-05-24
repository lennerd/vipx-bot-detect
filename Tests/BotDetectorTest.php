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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Vipx\BotDetect\BotDetector;
use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataCollection;
use Vipx\BotDetect\Metadata\MetadataInterface;

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

        self::assertArrayHasKey('debug', $options);
        self::assertTrue($options['debug']);
    }

    public function testMetadata(): void
    {
        $detector = $this->createDetector();
        $metadataCollection = $this->getMetadataCollection();

        self::assertEquals($metadataCollection->getMetadatas(), $detector->getMetadatas());
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

        $metadataCollection
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

            self::assertNotNull($detector->detect('Googlebot', '127.0.0.1'));
            self::assertFileExists($cacheFile);

            $cachedDetector = $this->createDetector();
            $cachedDetector->setOptions($options);

            self::assertNotNull($cachedDetector->detect('Googlebot', '127.0.0.1'));
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

        self::assertInstanceOf(MetadataInterface::class,
            $detector->detect('Googlebot', '127.0.0.1')
        );
        self::assertNull($detector->detect('VipxBot', '127.0.0.1'));
    }

    public function testUserAgentTrim(): void
    {
        $detector = $this->createDetector();
        self::assertInstanceOf(
            MetadataInterface::class,
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

            $loader
                ->method('load')
                ->willReturn($this->getMetadataCollection());

            $this->loader = $loader;
        }

        return $this->loader;
    }

    /**
     * @return MetadataCollection|MockObject
     */
    private function getMetadataCollection(): MetadataCollection
    {
        if (null === $this->metadataCollection) {
            $googleBot = new Metadata('Googlebot', 'Googlebot', ['127.0.0.1']);

            $metadatas = [
                $googleBot,
            ];

            $this->metadataCollection = $this->getMockBuilder(MetadataCollection::class)
                ->getMock();

            $this->metadataCollection
                ->method('getIterator')
                ->willReturnCallback(
                    function () use ($metadatas) {
                        return new \ArrayIterator($metadatas);
                    }
                );

            $this->metadataCollection
                ->method('getMetadatas')
                ->willReturn($metadatas);
        }

        return $this->metadataCollection;
    }

}
