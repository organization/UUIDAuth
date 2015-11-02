<?php

namespace ifteam\UUIDAuth\auth;

use ifteam\UUIDAuth\UUIDAuth;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\Player;

class AuthProvider {
	/**
	 *
	 * @var AuthProvider
	 */
	private static $instance = null;
	/**
	 *
	 * @var UUIDAuth
	 */
	private $plugin;
	/**
	 *
	 * @var AuthLoader
	 */
	private $loader;
	/**
	 *
	 * @var Server
	 */
	private $server;
	/**
	 *
	 * @var AuthProvider DB
	 */
	private $db;
	public function __construct(UUIDAuth $plugin) {
		if (self::$instance == null)
			self::$instance = $this;
		
		$this->plugin = $plugin;
		$this->loader = $plugin->getAuthLoader ();
		$this->server = Server::getInstance ();
		
		$this->db = (new Config ( $this->plugin->getDataFolder () . "pluginDB.yml", Config::YAML, [ ] ))->getAll ();
	}
	public function save($async = false) {
		$config = new Config ( $this->plugin->getDataFolder () . "pluginDB.yml", Config::YAML );
		$config->setAll ( $this->db );
		$config->save ( $async );
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
	public static function getInstance() {
		return static::$instance;
	}
}
?>