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

use Vipx\BotDetect\Metadata\Metadata;

class MetadataTest extends \PHPUnit_Framework_TestCase
{

    public function testMatchExact()
    {
        $metadata = new Metadata('TestBot', 'test', null, Metadata::TYPE_BOT, array(), Metadata::AGENT_MATCH_EXACT);

        $this->assertTrue($metadata->match('test', '127.0.0.1'));
    }

    public function testMatchRegexp()
    {
        $metadata = new Metadata('TestBot', 'test', null, Metadata::TYPE_BOT, array(), Metadata::AGENT_MATCH_REGEXP);

        $this->assertTrue($metadata->match('test-agent', '127.0.0.1'));
    }

}
