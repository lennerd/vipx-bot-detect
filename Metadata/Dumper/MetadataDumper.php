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

use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataInterface;

abstract class MetadataDumper implements MetadataDumperInterface
{

    private $metadatas;

    /**
     * @param MetadataInterface[] $metadatas
     */
    public function __construct(array $metadatas)
    {
        $this->metadatas = $metadatas;
    }

    /**
     * Returns all bot metadatas
     *
     * @return Metadata[]
     */
    public function getMetadatas(): array
    {
        return $this->metadatas;
    }

}
