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
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
		// TODO - 명령어처리용
		if (strtolower ( $command ) == $this->db->get ( "" )) { // TODO <- 빈칸에 명령어
			if (! isset ( $args [0] )) {
				// TODO - 명령어만 쳤을경우 도움말 표시
				return true;
			}
			switch (strtlower ( $args [0] )) {
				case $this->db->get ( "" ) :
					// TODO ↗ 빈칸에 세부명령어
					// TODO 세부명령어 실행시 원하는 작업 실행
					break;
				case $this->db->get ( "" ) :
					// TODO ↗ 빈칸에 세부명령어
					// TODO 세부명령어 실행시 원하는 작업 실행
					break;
				default :
					// TODO - 잘못된 명령어 입력시 도움말 표시
					break;
			}
			return true;
		}
	}
}

?>