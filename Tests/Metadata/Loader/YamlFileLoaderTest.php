<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect\Tests\Loader;

use PHPUnit\Framework\TestCase;
use Vipx\BotDetect\Metadata\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Vipx\BotDetect\Metadata\MetadataInterface;

class YamlFileLoaderTest extends TestCase
{

    public function testSupport()
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);

        $this->assertTrue($loader->supports('test.yml'));
        $this->assertFalse($loader->supports('test.xml'));
    }

    public function testEasyLoaderPath()
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);

        $metadatasAbsolute  = $loader->load(__DIR__ . '/../../../Resources/metadata/extended.yml')->getMetadatas();
        $metadatasEasy = $loader->load('extended.yml')->getMetadatas();

        $this->assertEquals($metadatasAbsolute, $metadatasEasy);
    }

    public function testParsing()
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);
        $metadataFile = __DIR__ . '/../../../Resources/metadata/extended.yml';

        $metadatas = $loader->load($metadataFile)->getMetadatas();

        $this->assertArrayHasKey('Googlebot', $metadatas);
        $this->assertArrayHasKey('vectra-mods', $metadatas);

        /** @var $metadata \Vipx\BotDetect\Metadata\Metadata */
        $metadata = $metadatas['Googlebot'];

        $this->assertEquals('Googlebot', $metadata->getAgent());
        $this->assertEquals(null, $metadata->getIp());
        $this->assertEquals(MetadataInterface::TYPE_BOT, $metadata->getType());

        /** @var $metadata \Vipx\BotDetect\Metadata\Metadata */
        $metadata = $metadatas['vectra-mods'];

        $this->assertEquals('', $metadata->getAgent());
        $this->assertEquals('212.227.101.211', $metadata->getIp());
        $this->assertEquals(MetadataInterface::TYPE_SPAMBOT, $metadata->getType());
    }

}
