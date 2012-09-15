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

interface MetadataDumperInterface
{

    /**
     * Dumps the given metadatas for saving into a file (e.g. cache)
     *
     * @return string
     */
    function dump();

}
