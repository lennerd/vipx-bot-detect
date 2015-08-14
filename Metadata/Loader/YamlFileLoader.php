<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect\Metadata\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Resource\FileResource;
use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataCollection;

class YamlFileLoader extends FileLoader
{

    /**
     * @param mixed $file
     * @param null|string $type
     * @return MetadataCollection
     */
    public function load($file, $type = null)
    {
        $file = $this->locator->locate($file, __DIR__.'/../../Resources/metadata');
        $collection = new MetadataCollection();

        $collection->addResource(new FileResource($file));

        $content = Yaml::parse( file_get_contents($file) );

        $this->parseImports($collection, $content, $file);
        $this->parseBots($collection, $content);

        return $collection;
    }

    /**
     * @param mixed $resource
     * @param null|string $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    /**
     * Parses all imports
     *
     * @param MetadataCollection $collection
     * @param array $content
     * @param string $file
     * @throws \Exception
     * @throws \Symfony\Component\Config\Exception\FileLoaderImportCircularReferenceException
     * @throws \Symfony\Component\Config\Exception\FileLoaderLoadException
     */
    private function parseImports(MetadataCollection $collection, $content, $file)
    {
        if (!isset($content['imports'])) {
            return;
        }

        foreach ($content['imports'] as $import) {
            $this->setCurrentDir(dirname($file));
            $collection->addCollection($this->import($import['resource'], null, isset($import['ignore_errors']) ? (Boolean) $import['ignore_errors'] : false, $file));
        }
    }

    /**
     * Parses all bot metadatas
     *
     * @param MetadataCollection $collection
     * @param $content
     * @return array
     */
    private function parseBots(MetadataCollection $collection, $content)
    {
        if (!isset($content['bots'])) {
            return;
        }

        foreach ($content['bots'] as $name => $bot) {
            $ip = isset($bot['ip']) ? $bot['ip'] : null;
            $metadata = new Metadata($name, $bot['agent'], $ip);

            if (isset($bot['type'])) {
                $metadata->setType($bot['type']);
            }

            if (isset($bot['meta'])) {
                $metadata->setMeta($bot['meta']);
            }

            if (isset($bot['agent_match'])) {
                $metadata->setAgentMatch($bot['agent_match']);
            }

            $collection->addMetadata($metadata);
        }
    }
}
