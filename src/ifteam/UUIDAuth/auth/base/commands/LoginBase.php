<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\command\CommandSender;

abstract class LoginBase extends CommandBase {
	public function registerCommand() {
		$database = PluginData::getInstance ();
		return $database->registerCommand ( $database->get ( "login" ), "UUIDAuth.login", $database->get ( "login-help" ), "/" . $database->get ( "login" ) );
	}
	public function onCommand(CommandSender $player, \pocketmine\command\Command $command, $label, array $args) {
	}
}

?>