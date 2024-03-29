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

class Metadata implements MetadataInterface
{

    private $name;
    private $agent;
    private $ip;
    private $type = self::TYPE_BOT;
    private $meta = [];
    private $agentMatch = self::AGENT_MATCH_REGEXP;

    /**
     * @param string $name
     * @param string $agent
     * @param null|string $ip
     * @param string $type
     * @param array $meta
     * @param string $agentMatch
     */
    public function __construct(string $name, string $agent, $ip = null)
    {
        $this->name = $name;
        $this->agent = $agent;
        $this->ip = $ip;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param array $meta
     */
    public function setMeta(array $meta): void
    {
        $this->meta = $meta;
    }

    /**
     * @param string $agentMatch
     */
    public function setAgentMatch(string $agentMatch): void
    {
        $this->agentMatch = $agentMatch;
    }

    /**
     * {@inheritdoc}
     */
    public function getAgent(): string
    {
        return $this->agent;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function getAgentMatch(): string
    {
        return $this->agentMatch;
    }

    /**
     * {@inheritdoc}
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function match(string $agent, string $ip): bool
    {
        if ((self::AGENT_MATCH_EXACT === $this->agentMatch && $this->agent !== $agent) ||
            (self::AGENT_MATCH_REGEXP === $this->agentMatch && !@preg_match('#'.$this->agent.'#', $agent))) {
            return false;
        }

        if (is_null($this->ip)) {
            return true;
        }

        if (is_array($this->ip)) {
            return in_array($ip, $this->ip, true);
        }

        return $this->ip === $ip;
    }

}
