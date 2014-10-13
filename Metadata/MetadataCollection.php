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

use Symfony\Component\Config\Resource\ResourceInterface;

class MetadataCollection implements \IteratorAggregate
{

    private $resources = array();
    private $metadatas = array();

    public function addCollection(MetadataCollection $collection)
    {
        foreach ($collection->getMetadatas() as $metadata) {
            $this->addMetadata($metadata);
        }

        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }

    public function addMetadata(MetadataInterface $metadata)
    {
        $this->metadatas[$metadata->getName()] = $metadata;
    }

    public function getMetadatas()
    {
        return $this->metadatas;
    }

    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
    }

    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->metadatas);
    }
}
