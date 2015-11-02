<?php

namespace ifteam\UUIDAuth\auth;

use ifteam\UUIDAuth\UUIDAuth;
use pocketmine\Server;
use pocketmine\Player;

class AuthDataProvider {
	/**
	 *
	 * @var AuthDataProvider
	 */
	private static $instance = null;
	/**
	 *
	 * @var UUIDAuth
	 */
	private $plugin;
	/**
	 *
	 * @var AuthDataLoader
	 */
	private $loader;
	/**
	 *
	 * @var Server
	 */
	private $server;
	public function __construct(UUIDAuth $plugin) {
		if (self::$instance == null)
			self::$instance = $this;
		
		$this->plugin = $plugin;
		$this->loader = $plugin->getAuthLoader ();
		$this->server = Server::getInstance ();
	}
	
	/**
	 * Create a default setting
	 *
	 * @param string $userName        	
	 */
	public function loadAuth($userName) {
		return $this->loader->loadAuth ( $userName );
	}
	public function unloadAuth($userName = null) {
		return $this->loader->unloadAuth ( $userName );
	}
	public function getAuth(Player $player) {
		return $this->loader->getAuth ( $player );
	}
	public function getAuthToName($name) {
		return $this->loader->getAuthToName ( $name );
	}
	public function checkToExistDataToName($name) {
		return $this->loader->checkToExistDataToName ( $name );
	}
	public static function getInstance() {
		return static::$instance;
	}
}
?>