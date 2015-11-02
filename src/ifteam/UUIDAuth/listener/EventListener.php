<?php

namespace ifteam\UUIDAuth\listener;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
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
		
		// $this->registerCommand("명령어이름", "퍼미션명", "명령어설명", "명령어사용법-한줄");
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $plugin );
	}
	public function registerCommand($name, $permission, $description, $usage) {
		$name = $this->db->get ( $name );
		$description = $this->db->get ( $description );
		$usage = $this->db->get ( $usage );
		$this->db->registerCommand ( $name, $permission, $description, $usage );
	}
	public function getServer() {
		return $this->server;
	}
}

?>