<?php declare(strict_types=1);

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
     *
     */
    protected function read()
    {
        $url        = $this->settings->get('url');
        $fullscreen = $this->settings->get('fullscreen');

        if (! is_bool($url) && strlen($url)) {
            $this->setUrl($url);
        }

        $this->setFullScreen($fullscreen);
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

    /**
     *
     */
    public function save()
    {
        $this->settings->set('url', rtrim($this->getUrl(), '/'));
        $this->settings->set('fullscreen', $this->getFullScreen());
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
        global $ilDB;

        return $ilDB;
    }

}