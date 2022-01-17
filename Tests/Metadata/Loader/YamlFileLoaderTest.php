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
use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataInterface;

class YamlFileLoaderTest extends TestCase
{

    public function testSupport(): void
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);

        self::assertTrue($loader->supports('test.yml'));
        self::assertFalse($loader->supports('test.xml'));
    }

    public function testEasyLoaderPath(): void
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);

        $metadatasAbsolute = $loader->load(__DIR__.'/../../../Resources/metadata/extended.yml')->getMetadatas();
        $metadatasEasy = $loader->load('extended.yml')->getMetadatas();

        self::assertEquals($metadatasAbsolute, $metadatasEasy);
    }

    public function testParsing(): void
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);
        $metadataFile = __DIR__.'/../../../Resources/metadata/extended.yml';

        $metadatas = $loader->load($metadataFile)->getMetadatas();

        self::assertArrayHasKey('Googlebot', $metadatas);
        self::assertArrayHasKey('vectra-mods', $metadatas);

        /** @var $metadata Metadata */
        $metadata = $metadatas['Googlebot'];

        self::assertEquals('Googlebot', $metadata->getAgent());
        self::assertEquals(null, $metadata->getIp());
        self::assertEquals(MetadataInterface::TYPE_BOT, $metadata->getType());

        /** @var $metadata Metadata */
        $metadata = $metadatas['vectra-mods'];

        self::assertEquals('', $metadata->getAgent());
        self::assertEquals('212.227.101.211', $metadata->getIp());
        self::assertEquals(MetadataInterface::TYPE_SPAMBOT, $metadata->getType());
    }

}
