<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect\Metadata;

use ArrayIterator;
use Symfony\Component\Config\Resource\ResourceInterface;
use Traversable;

class MetadataCollection implements \IteratorAggregate
{

    private $resources = [];
    private $metadatas = [];

    public function addCollection(MetadataCollection $collection): void
    {
        foreach ($collection->getMetadatas() as $metadata) {
            $this->addMetadata($metadata);
        }

        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }

    public function addMetadata(MetadataInterface $metadata): void
    {
        $this->metadatas[$metadata->getName()] = $metadata;
    }

    public function getMetadatas(): array
    {
        return $this->metadatas;
    }

    public function addResource(ResourceInterface $resource): void
    {
        $this->resources[] = $resource;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->metadatas);
    }
}
