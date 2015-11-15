<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

interface Command {
	public function registerCommand();
	/**
	 *
	 * @param CommandSender $player        	
	 * @param Command $command        	
	 * @param string $label        	
	 * @param array $args        	
	 */
	public function onCommand(CommandSender $player, Command $command, $label, array $args);
	/**
	 *
	 * @return Command
	 */
	public function getCommand();
}

?>