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

    const AGENT_MATCH_EXACT = 'exact';
    const AGENT_MATCH_REGEXP = 'regexp';

    const TYPE_BOT = 'bot';
    const TYPE_CRAWLER = 'crawler';
    const TYPE_SPIDER = 'spider';
    const TYPE_SPAMBOT = 'spambot';
    const TYPE_RSS = 'rss';
    const TYPE_VALIDATOR = 'validator';

    /**
     * Returns the name of the bot
     *
     * @return string
     */
    function getName();

    /**
     * Returns true if the given agent and ip matches the agent and ip of the bot
     *
     * @param string $agent
     * @param string $ip
     * @return bool
     */
    function match($agent, $ip);

    /**
     * Returns the agent of the bot
     *
     * @return string
     */
    function getAgent();

    /**
     * Returns the type of the agent check
     *
     * @return string
     */
    function getAgentMatch();

    /**
     * Returns the ip of the bot
     *
     * @return null|string
     */
    function getIp();

    /**
     * Returns the type of the bot
     *
     * @return string
     */
    function getType();

    /**
     * Returns the meta information's of the bot
     *
     * @return array
     */
    function getMeta();

}
