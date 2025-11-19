<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

class ilEtherCalcPlugin extends ilRepositoryObjectPlugin
{
    /** @var string */
    public const ID = "xetc";

    /** @var string */
    public const CTYPE = 'Services';

    /** @var string */
    public const CNAME = 'Repository';

    /** @var string */
    public const SLOT_ID = 'robj';

    /** @var string */
    public const PNAME = 'EtherCalc';

    public function getPluginName(): string
    {
        return self::PNAME;
    }
    protected function uninstallCustom(): void
    {
        global $DIC;
        $DIC->database()->query('DROP TABLE rep_robj_xetc_data');
    }

    public function getAssetURL(string $relative_path, bool $versioned = true): string
    {
        $version_suffix = $versioned ? '?version=' . str_replace('.', '-', $this->getVersion()) : '';
        $url = $this->getDirectory() . '/templates/' . ltrim($relative_path, '/') . $version_suffix;
        return $this->buildHttpUrl($url);
    }

    protected function buildHttpUrl(string $path): string
    {
        $cleaned_url = explode("public", $path);
        if (isset($cleaned_url[1])) {
            $cleaned_url = $cleaned_url[1];
        }
        # $http_path = ilUtil::_getHttpPath();
        return  $cleaned_url;
    }

}
