<?php

namespace ifteam\UUIDAuth\listener;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class EventListener implements Listener {
	/**
	 *
	 * @var Plugin
	 */
	private $plugin;
	private $db;
	/**
	 *
	 * @var Server
	 */
	private $server;
	public function __construct(Plugin $plugin) {
		$this->plugin = $plugin;
		$this->db = PluginData::getInstance ();
		$this->server = Server::getInstance ();
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $plugin );
	}
	public function getServer() {
		return $this->server;
	}
}

?>