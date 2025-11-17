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

class ilEtherCalcConfig
{
    private static ?self $instance = null;

    protected ilSetting $settings;

    protected string $url = "";

    protected int $fullscreen = 0;

    private function __construct()
    {
        $this->settings = new ilSetting('ilethercalcplugin');
        $this->read();
    }

    public static function getInstance(): self
    {
        return self::$instance ?? (self::$instance = new self());
    }

    protected function read(): void
    {
        $url = $this->settings->get('url');
        $fullscreen = (int) $this->settings->get('fullscreen', "0");

        if (!is_null($url) && !is_bool($url) && $url !== '') {
            $this->setUrl($url);
        }

        $this->setFullScreen($fullscreen);
    }

    public function getSettings(): ilSetting
    {
        return $this->settings;
    }

    public function setSettings(ilSetting $settings): void
    {
        $this->settings = $settings;
    }

    public function save(): void
    {
        $this->settings->set('url', rtrim($this->getUrl(), '/'));
        $this->settings->set('fullscreen', (string) $this->getFullScreen());
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getFullScreen(): int
    {
        return $this->fullscreen;
    }

    public function setFullScreen(int $full_screen): void
    {
        $this->fullscreen = $full_screen;
    }

}
