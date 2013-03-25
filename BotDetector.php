<?php

/*
 * This file is part of the VipxBotDetect library.
 *
 * (c) Lennart Hildebrandt <http://github.com/lennerd>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vipx\BotDetect;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\ConfigCache;

class BotDetector implements BotDetectorInterface
{

    private $metadatas;
    private $loader;
    private $options;

    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param $resource
     * @param array $options
     */
    public function __construct(LoaderInterface $loader, array $options = array())
    {
        $this->loader = $loader;

        $this->setOptions($options);
    }

    /**
     * @param array $options
     * @throws \InvalidArgumentException
     */
    public function setOptions(array $options)
    {
        $this->options = array(
            'resource'              => __DIR__ . '/Resources/metadata/basic_bot.yml',
            'cache_dir'             => null,
            'debug'                 => false,
            'metadata_cache_file'   => 'project_vipx_bot_detect_metadata.php',
            'metadata_dumper_class' => 'Vipx\\BotDetectBundle\\Bot\\Metadata\\Dumper\\MetadataDumper',
        );

        // check option names and live merge, if errors are encountered Exception will be thrown
        $invalid = array();
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new \InvalidArgumentException(sprintf('The BotDetector does not support the following options: "%s".', implode('\', \'', $invalid)));
        }
    }

    /**
     * @return \Vipx\BotDetect\Metadata\Metadata[]
     */
    public function getMetadatas()
    {
        if (null !== $this->metadatas) {
            return $this->metadatas;
        }

        $resource = $this->options['resource'];

        if (null === $this->options['cache_dir'] || null === $this->options['metadata_cache_file']) {
            /** @var $metadataCollection \Vipx\BotDetect\Metadata\MetadataCollection */
            $metadataCollection = $this->loader->load($resource);

            return $this->metadatas = $metadataCollection->getMetadatas();
        }

        $cache = new ConfigCache($this->options['cache_dir'] . '/' . $this->options['metadata_cache_file'], $this->options['debug']);

        if (!$cache->isFresh()) {
            /** @var $metadataCollection \Vipx\BotDetect\Metadata\MetadataCollection */
            $metadataCollection = $this->loader->load($resource);
            $dumperClass = $this->options['metadata_dumper_class'];

            /** @var $dumper \Vipx\BotDetect\Metadata\Dumper\MetadataDumper */
            $dumper = new $dumperClass($metadataCollection->getMetadatas());

            $cache->write($dumper->dump(), $metadataCollection->getResources());
        }

        return $this->metadatas = require $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function detect($agent, $ip)
    {
        foreach ($this->getMetadatas() as $metadata) {
            if ($metadata->match($agent, $ip)) {
                return $metadata;
            }
        }

        return null;
    }

}
