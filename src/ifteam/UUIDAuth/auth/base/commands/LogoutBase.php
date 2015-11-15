<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\command\CommandSender;

abstract class LogoutBase extends CommandBase {
	public function registerCommand() {
		$database = PluginData::getInstance ();
		return $database->registerCommand ( $database->get ( "logout" ), "UUIDAuth.logout", $database->get ( "logout-help" ), "/" . $database->get ( "logout" ) );
	}
	public function onCommand(CommandSender $player, \pocketmine\command\Command $command, $label, array $args) {
	}
}

?>