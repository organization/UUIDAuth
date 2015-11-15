<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use pocketmine\command\CommandSender;
use ifteam\UUIDAuth\auth\base\AuthBase;

abstract class CommandBase implements Command {
	/**
	 *
	 * @var AuthBase
	 */
	private $auth;
	/**
	 *
	 * @var Command
	 */
	private $command = null;
	/**
	 *
	 * @param AuthBase $auth        	
	 */
	public function __construct(AuthBase $auth) {
		$this->auth = $auth;
		$this->command = $this->registerCommand ();
	}
	public function registerCommand() {
	}
	public function onCommand(CommandSender $player, \pocketmine\command\Command $command, $label, array $args) {
	}
	/**
	 *
	 * @return AuthBase
	 */
	public function getAuth() {
		return $this->auth;
	}
	/**
	 *
	 * @return Command
	 */
	public function getCommand() {
		return $this->command;
	}
}

?>