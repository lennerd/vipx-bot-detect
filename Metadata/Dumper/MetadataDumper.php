<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect\Metadata\Dumper;

abstract class MetadataDumper implements MetadataDumperInterface
{

    private $metadatas = array();

    /**
     * @param \Vipx\BotDetect\Metadata\MetadataInterface[] $metadatas
     */
    public function __construct(array $metadatas)
    {
        $this->metadatas = $metadatas;
    }

    /**
     * Returns all bot metadatas
     *
     * @return \Vipx\BotDetect\Metadata\Metadata[]
     */
    public function getMetadatas()
    {
        return $this->metadatas;
    }

}
