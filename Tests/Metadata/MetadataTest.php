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

    public function testMatchRegexp()
    {
        $metadata = $this->createMetadata('test');
        $metadata->setAgentMatch(Metadata::AGENT_MATCH_REGEXP);

        $this->assertTrue($metadata->match('test-agent', '127.0.0.1'));
        $this->assertFalse($metadata->match('foo', '127.0.0.1'));
    }

    public function testMatchExact()
    {
        $metadata = $this->createMetadata('test');

        $this->assertTrue($metadata->match('test', '127.0.0.1'));
        $this->assertFalse($metadata->match('test-agent', '127.0.0.1'));
    }

    public function testMatchIp()
    {
        $metadata = $this->createMetadata('test', '127.0.0.1');

        $this->assertTrue($metadata->match('test', '127.0.0.1'));
        $this->assertFalse($metadata->match('test', '127.0.0.2'));
    }

    public function testMatchIpArray()
    {
        $metadata = $this->createMetadata('test', array('127.0.0.1', '127.0.0.2'));

        $this->assertTrue($metadata->match('test', '127.0.0.1'));
        $this->assertFalse($metadata->match('test', '127.0.0.3'));
    }

    public function testMeta()
    {
        $meta = array('foo' => 'bar');

        $metadata = $this->createMetadata('test');
        $metadata->setMeta($meta);

        $this->assertEquals($meta, $metadata->getMeta());
    }

    public function testAgentMatch()
    {
        $match = Metadata::AGENT_MATCH_REGEXP;

        $metadata = $this->createMetadata('test');
        $metadata->setAgentMatch($match);

        $this->assertEquals($match, $metadata->getAgentMatch());
    }

    private function createMetadata($agent, $ip = null)
    {
        $metadata = new Metadata('TestBot', $agent, $ip, Metadata::TYPE_BOT);
        $metadata->setAgentMatch(Metadata::AGENT_MATCH_EXACT);

        return $metadata;
    }

}
