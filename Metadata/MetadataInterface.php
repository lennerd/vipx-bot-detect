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

interface MetadataInterface
{

    public const AGENT_MATCH_EXACT = 'exact';
    public const AGENT_MATCH_REGEXP = 'regexp';

    public const TYPE_BOT = 'bot';
    public const TYPE_CRAWLER = 'crawler';
    public const TYPE_SPIDER = 'spider';
    public const TYPE_SPAMBOT = 'spambot';
    public const TYPE_RSS = 'rss';
    public const TYPE_VALIDATOR = 'validator';

    /**
     * Returns the name of the bot
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns true if the given agent and ip matches the agent and ip of the bot
     *
     * @param string $agent
     * @param string|null $ip
     * @return bool
     */
    public function match(string $agent, ?string $ip): bool;

    /**
     * Returns the agent of the bot
     *
     * @return string
     */
    public function getAgent(): string;

    /**
     * Returns the type of the agent check
     *
     * @return string
     */
    public function getAgentMatch(): string;

    /**
     * Returns the ips of the bot (if any)
     *
     * @return array
     */
    public function getIps(): array;

    /**
     * Returns the type of the bot
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Returns the meta information's of the bot
     *
     * @return array
     */
    public function getMeta(): array;

}
