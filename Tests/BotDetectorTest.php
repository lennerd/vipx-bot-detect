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

class BotDetectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidOptions()
    {
        $detector = $this->getDetector();

        $detector->setOptions(array(
            'invalid_options' => 'value'
        ));
    }

    public function testValidOptions()
    {
        $options = array(
            'debug' => true
        );

        $detector = $this->getDetector();
        $detector->setOptions($options);
        $options = $detector->getOptions();

        $this->assertArrayHasKey('debug', $options);
        $this->assertTrue($options['debug']);
    }

    public function testDetection()
    {
        $detector = $this->getDetector();

        $this->assertEquals($detector->detect('Googlebot', '')->getName(), 'Google');
        $this->assertEquals($detector->detect('', '212.227.101.211')->getName(), 'AboutUs');
        $this->assertNull($detector->detect('VipxBot', ''));
    }

    private function getDetector()
    {
        $locator = new FileLocator();
        $loader = new YamlFileLoader($locator);
        $metadataFile = __DIR__ . '/../Resources/metadata/extended.yml';

        return new BotDetector($loader, $metadataFile);
    }

}
