<?php

namespace ifteam\UUIDAuth\auth\network\slave;

use ifteam\UUIDAuth\auth\AuthBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class SlaveAuth extends AuthBase implements Listener {
	public function onLoad() {
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this->getPlugin () );
	}
	public function authenticatePlayer($player) {
	}
	public function deauthenticatePlayer($player) {
	}
	public function cueAuthenticatePlayer($player) {
	}
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
	}
}

?>