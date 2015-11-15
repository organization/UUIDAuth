<?php

namespace ifteam\UUIDAuth\auth\network\master;

use pocketmine\event\Listener;
use ifteam\UUIDAuth\auth\base\AuthBase;
use pocketmine\Player;

class MasterAuth extends AuthBase implements Listener {
	public $needAuth = [ ];
	/**
	 * Prevent brute forcing
	 */
	public $wrongauth = [ ];
	private $commands;
	public function onLoad() {
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this->getPlugin () );
		
		$this->registerCommand ( new LoginCommand ( $this ) );
		$this->registerCommand ( new LogoutCommand ( $this ) );
		$this->registerCommand ( new RegisterCommand ( $this ) );
		$this->registerCommand ( new UnregisterCommand ( $this ) );
		
		new AuthServer ( $this->getPlugin () );
	}
	public function authenticatePlayer(Player $player) {
	}
	public function deauthenticatePlayer(Player $player) {
	}
	public function cueAuthenticatePlayer(Player $player) {
	}
}

?>