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

interface BotDetectorInterface
{

    /**
     * Tries to detect if the given request is made by a bot
     *
     * @param string $agent
     * @param string $ip
     * @return null|\Vipx\BotDetect\Metadata\Metadata
     */
    function detect($agent, $ip);

}
