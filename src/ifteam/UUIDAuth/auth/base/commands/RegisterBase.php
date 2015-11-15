<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\command\CommandSender;

abstract class RegisterBase extends CommandBase {
	public function registerCommand() {
		$database = PluginData::getInstance ();
		return $database->registerCommand ( $database->get ( "register" ), "UUIDAuth.register", $database->get ( "register-help" ), "/" . $database->get ( "register" ) );
	}
	public function onCommand(CommandSender $player, \pocketmine\command\Command $command, $label, array $args) {
	}
}

?>