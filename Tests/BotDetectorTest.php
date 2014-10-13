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

use Vipx\BotDetect\BotDetector;
use Symfony\Component\Config\FileLocator;
use Vipx\BotDetect\Metadata\Loader\YamlFileLoader;
use Vipx\BotDetect\Metadata\MetadataInterface;

class BotDetectorTest extends \PHPUnit_Framework_TestCase
{

    private $metadatas;
    private $loader;

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOptions()
    {
        $detector = $this->createDetector();

        $detector->setOptions(array(
            'invalid_options' => 'value'
        ));
    }

    public function testValidOptions()
    {
        $options = array(
            'debug' => true
        );

        $detector = $this->createDetector();
        $detector->setOptions($options);
        $options = $detector->getOptions();

        $this->assertArrayHasKey('debug', $options);
        $this->assertTrue($options['debug']);
    }

    public function testCacheOptions()
    {
        $cacheFile = tempnam(sys_get_temp_dir(), 'vipx_bot_detect_test_metadata_');

        $options = array(
            'cache_dir' => dirname($cacheFile),
            'metadata_cache_file' => basename($cacheFile),
        );

        $detector = $this->createDetector();
        $detector->setOptions($options);

        $this->assertEquals(require $cacheFile, $detector->getMetadatas());

        unlink($cacheFile);
    }

    public function testDetection()
    {
        $detector = $this->createDetector();

        $this->assertInstanceOf('Vipx\BotDetect\Metadata\MetadataInterface', $detector->detect('Googlebot', '127.0.0.1'));
        $this->assertNull($detector->detect('VipxBot', '127.0.0.1'));
    }

    private function createDetector()
    {
        return new BotDetector($this->getLoader(), '/my/vipx/bot/file.yml');
    }

    private function getLoader()
    {
        if (null === $this->loader) {
            $loader = $this->getMock('Symfony\Component\Config\Loader\LoaderInterface');

            $loader->expects($this->any())
                ->method('load')
                ->will($this->returnValue($this->getMetadatas()));

            $this->loader = $loader;
        }

        return $this->loader;
    }

    private function getMetadatas()
    {
        if (null === $this->metadatas) {
            $googleBot = $this->getMock('Vipx\BotDetect\Metadata\MetadataInterface');

            $googleBot->expects($this->any())
                ->method('match')
                ->will($this->returnCallback(function($agent, $ip) {
                    return $agent == 'Googlebot' && $ip === '127.0.0.1';
                }));

            $this->metadatas = array(
                $googleBot,
            );
        }

        return $this->metadatas;
    }

}
