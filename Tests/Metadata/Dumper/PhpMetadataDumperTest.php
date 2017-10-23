<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetectBundle\Tests\Dumper;

use PHPUnit\Framework\TestCase;
use Vipx\BotDetect\Metadata\Dumper\PhpMetadataDumper;
use Vipx\BotDetect\Metadata\MetadataInterface;

class PhpMetadataDumperTest extends TestCase
{

    private $metadatas;
    private $dumper;
    private $testTmpFilePath;

    protected function setUp()
    {
        parent::setUp();

        $metadata = $this->getMockBuilder(MetadataInterface::class)
            ->getMock();

        $metadata->expects($this->any())
            ->method('getAgent')
            ->will($this->returnValue('TestBot'));

        $metadata->expects($this->any())
            ->method('getIp')
            ->will($this->returnValue(null));

        $metadata->expects($this->any())
            ->method('getType')
            ->will($this->returnValue(MetadataInterface::TYPE_BOT));

        $metadata->expects($this->any())
            ->method('getAgentMatch')
            ->will($this->returnValue(MetadataInterface::AGENT_MATCH_REGEXP));

        $this->metadatas = array(
            'TestBot' => $metadata,
        );
        $this->dumper = new PhpMetadataDumper($this->metadatas);

        $this->testTmpFilePath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'project_vipx_bot_detect_metadata.php';
        @unlink($this->testTmpFilePath);
    }

    protected function tearDown()
    {
        parent::tearDown();

        @unlink($this->testTmpFilePath);

        $this->metadatas = null;
        $this->dumper = null;
        $this->testTmpFilePath = null;
    }

    public function testDump()
    {
        file_put_contents($this->testTmpFilePath, $this->dumper->dump());
        $metadatas = require $this->testTmpFilePath;

        $this->assertArrayHasKey('TestBot', $metadatas);
        $this->assertEquals($metadatas['TestBot'], $this->metadatas['TestBot']);
    }

}
