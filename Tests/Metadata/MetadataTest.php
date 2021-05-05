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

use PHPUnit\Framework\TestCase;
use Vipx\BotDetect\Metadata\Metadata;

class MetadataTest extends TestCase
{

    public function testMatchRegexp(): void
    {
        $metadata = $this->createMetadata('test');
        $metadata->setAgentMatch(Metadata::AGENT_MATCH_REGEXP);

        self::assertTrue($metadata->match('test-agent', '127.0.0.1'));
        self::assertFalse($metadata->match('foo', '127.0.0.1'));
    }

    public function testMatchExact(): void
    {
        $metadata = $this->createMetadata('test');

        self::assertTrue($metadata->match('test', '127.0.0.1'));
        $this->assertFalse($metadata->match('test-agent', '127.0.0.1'));
    }

    public function testMatchIp(): void
    {
        $metadata = $this->createMetadata('test', ['127.0.0.1']);

        self::assertTrue($metadata->match('test', '127.0.0.1'));
        self::assertFalse($metadata->match('test', '127.0.0.2'));
    }

    public function testMatchIpArray(): void
    {
        $metadata = $this->createMetadata('test', ['127.0.0.1', '127.0.0.2']);

        self::assertTrue($metadata->match('test', '127.0.0.1'));
        self::assertFalse($metadata->match('test', '127.0.0.3'));
    }

    public function testMeta(): void
    {
        $meta = ['foo' => 'bar'];

        $metadata = $this->createMetadata('test');
        $metadata->setMeta($meta);

        self::assertEquals($meta, $metadata->getMeta());
    }

    public function testAgentMatch(): void
    {
        $match = Metadata::AGENT_MATCH_REGEXP;

        $metadata = $this->createMetadata('test');
        $metadata->setAgentMatch($match);

        self::assertEquals($match, $metadata->getAgentMatch());
    }

    private function createMetadata(string $agent, array $ips = []): Metadata
    {
        $metadata = new Metadata('TestBot', $agent, $ips);
        $metadata->setAgentMatch(Metadata::AGENT_MATCH_EXACT);

        return $metadata;
    }

}
