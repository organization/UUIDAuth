<?php

namespace ifteam\UUIDAuth\auth\base\commands;

use ifteam\UUIDAuth\database\PluginData;
use pocketmine\command\CommandSender;

abstract class UnregisterBase extends CommandBase {
	public function registerCommand() {
		$database = PluginData::getInstance ();
		return $database->registerCommand ( $database->get ( "unregister" ), "UUIDAuth.unregister", $database->get ( "unregister-help" ), "/" . $database->get ( "unregister" ) );
	}
	public function onCommand(CommandSender $player, \pocketmine\command\Command $command, $label, array $args) {
	}
}

?>