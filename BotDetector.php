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

use InvalidArgumentException;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\ConfigCache;
use Vipx\BotDetect\Metadata\Dumper\MetadataDumper;
use Vipx\BotDetect\Metadata\Dumper\PhpMetadataDumper;
use Vipx\BotDetect\Metadata\Metadata;
use Vipx\BotDetect\Metadata\MetadataCollection;

class BotDetector implements BotDetectorInterface
{

    private $metadatas;
    private $loader;
    private $resource;
    private $options;

    /**
     * @param LoaderInterface $loader
     * @param $resource
     * @param array $options
     */
    public function __construct(LoaderInterface $loader, $resource, array $options = [])
    {
        $this->loader = $loader;
        $this->resource = $resource;

        $this->setOptions($options);
    }

    /**
     * @param array $options
     * @throws InvalidArgumentException
     */
    public function setOptions(array $options): void
    {
        $this->options = [
            'cache_dir' => null,
            'debug' => false,
            'metadata_cache_file' => 'project_vipx_bot_detect_metadata.php',
            'metadata_dumper_class' => PhpMetadataDumper::class,
        ];

        // check option names and live merge, if errors are encountered Exception will be thrown
        $invalid = [];
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->options)) {
                $this->options[$key] = $value;
            } else {
                $invalid[] = $key;
            }
        }

        if ($invalid) {
            throw new InvalidArgumentException(
                sprintf('The BotDetector does not support the following options: "%s".', implode('\', \'', $invalid))
            );
        }
    }

    /**
     * Returns the detector options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return Metadata[]
     */
    public function getMetadatas(): array
    {
        if (null !== $this->metadatas) {
            return $this->metadatas;
        }

        if (null === $this->options['cache_dir'] || null === $this->options['metadata_cache_file']) {
            $metadataCollection = $this->loader->load($this->resource);

            return $this->metadatas = $metadataCollection->getMetadatas();
        }

        $cache = new ConfigCache(
            $this->options['cache_dir'].'/'.$this->options['metadata_cache_file'],
            $this->options['debug']
        );

        if ($cache->isFresh()) {
            return $this->metadatas = require $cache->getPath();
        }

        /** @var $metadataCollection MetadataCollection */
        $metadataCollection = $this->loader->load($this->resource);
        $dumperClass = $this->options['metadata_dumper_class'];
        $metadatas = $metadataCollection->getMetadatas();

        /** @var $dumper MetadataDumper */
        $dumper = new $dumperClass($metadatas);

        $cache->write($dumper->dump(), $metadataCollection->getResources());

        return $this->metadatas = $metadatas;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $agent, string $ip): ?Metadata
    {
        $agent = trim($agent);
        foreach ($this->getMetadatas() as $metadata) {
            if ($metadata->match($agent, $ip)) {
                return $metadata;
            }
        }

        return null;
    }

}
