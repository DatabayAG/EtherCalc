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

/**
 * Class ilEtherCalcConfig
 */
class ilEtherCalcConfig
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var ilSetting
     */
    protected $settings;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $fullscreen;

    /**
     * ilEtherCalcConfig constructor.
     */
    private function __construct()
    {
        $this->settings = new ilSetting('ilethercalcplugin');
        $this->read();
    }

    /**
     * Get singleton instance
     * @return self
     */
    public static function getInstance()
    {
        if (null !== self::$instance) {
            return self::$instance;
        }

        return (self::$instance = new self());
    }

    protected function read()
    {
        $url = $this->settings->get('url');
        $fullscreen = $this->settings->get('fullscreen');

        if (!is_null($url) && !is_bool($url) && strlen($url)) {
            $this->setUrl($url);
        }

        $this->setFullScreen($fullscreen);
    }

    /**
     * @return ilSetting
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param ilSetting $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    public function save()
    {
        $this->settings->set('url', rtrim($this->getUrl(), '/'));
        $this->settings->set('fullscreen', (string) $this->getFullScreen());
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getFullScreen()
    {
        return $this->fullscreen;
    }

    /**
     * @param int $full_screen
     */
    public function setFullScreen($full_screen)
    {
        $this->fullscreen = $full_screen;
    }

    /**
     * @return ilDBInterface
     */
    public function getDatabaseAdapter()
    {
        /**
         * @var $ilDB ilDBInterface
         */
        global $DIC;

        return $DIC->database();
    }

}
