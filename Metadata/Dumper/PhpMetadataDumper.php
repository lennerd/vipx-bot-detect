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

use Vipx\BotDetect\Metadata\MetadataInterface;

class PhpMetadataDumper extends MetadataDumper
{

    /**
     * {@inheritdoc}
     */
    public function dump()
    {
        return <<<EOF
<?php

/**
 * This file has been auto-generated
 * by the VipxBotDetect package.
 */

\$metdatas = array();

{$this->dumpMetadatas()}

return \$metdatas;
EOF;
    }

    private function dumpMetadatas()
    {
        $metadatas = $this->getMetadatas();
        $dump = array();

        foreach ($metadatas as $name => $metadata) {
            /* $ip = null, $type = self::TYPE_BOT, $agentMatch = self::AGENT_MATCH_REGEXP */
            $dump[] = sprintf("\$metdatas['%s'] = new %s('%s', '%s', %s);",
                $name,
                get_class($metadata),
                $name,
                $metadata->getAgent(),
                var_export($metadata->getIp(), true));

            $type = $metadata->getType();

            if ($type !== MetadataInterface::TYPE_BOT) {
                $dump[] = sprintf("\$metdatas['%s']->setType(%s);", $name, var_export($type, true));
            }

            $meta = $metadata->getMeta();

            if (!empty($meta)) {
                $dump[] = sprintf("\$metdatas['%s']->setMeta(%s);", $name, var_export($meta, true));
            }

            $agentMatch = $metadata->getAgentMatch();

            if ($agentMatch !== MetadataInterface::AGENT_MATCH_REGEXP) {
                $dump[] = sprintf("\$metdatas['%s']->setAgentMatch(%s);", $name, var_export($agentMatch, true));
            }
        }

        return implode($dump, "\n");
    }

}
