<?php

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
	 * @var int
	 */
	protected $port;

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
	 *
	 * @return self
	 */
	public static function getInstance()
	{
		if(null !== self::$instance)
		{
			return self::$instance;
		}

		return (self::$instance = new self());
	}

	/**
	 *
	 */
	protected function read()
	{
		$url		= $this->settings->get('url');
		$port		= $this->settings->get('port');
		$fullscreen	= $this->settings->get('fullscreen');

		if(strlen($url))
		{
			$this->setUrl($url);
		}
		if(strlen($port))
		{
			$this->setPort($port);
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

	/**
	 *
	 */
	public function save()
	{
		$this->settings->set('url', $this->getUrl());
		$this->settings->set('port', $this->getPort());
		$this->settings->set('fullscreen', $this->getFullScreen());
	}

	/**
	 * @return ilDB
	 */
	public function getDatabaseAdapter()
	{
		/**
		 * @var $ilDB ilDB
		 */
		global $ilDB;

		return $ilDB;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param int $port
	 */
	public function setPort($port)
	{
		$this->port = $port;
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
	 * @param int $fullscreen
	 */
	public function setFullScreen($fullscreen)
	{
		$this->fullscreen = $fullscreen;
	}



}