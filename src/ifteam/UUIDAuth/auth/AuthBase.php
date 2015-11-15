<?php

namespace ifteam\UUIDAuth\auth\base;

use pocketmine\Server;
use ifteam\UUIDAuth\UUIDAuth;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

abstract class AuthBase extends Auth {
	/** @var \ifteam\UUIDAuth\auth\AuthDataProvider */
	private $provider;
	/** @var \pocketmine\Server */
	private $server;
	/** @var \ifteam\UUIDAuth\UUIDAuth  */
	private $plugin;
	public final function init(AuthDataProvider $provider, Server $server, UUIDAuth $plugin) {
		$this->provider = $provider;
		$this->server = $server;
		$this->plugin = $plugin;
		$this->onLoad ();
	}
	public function onLoad() {
	}
	public function authenticatePlayer($player) {
	}
	public function deauthenticatePlayer($player) {
	}
	public function cueAuthenticatePlayer($player) {
	}
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
	}
	/**
	 * Uses SHA-512 [http://en.wikipedia.org/wiki/SHA-2] and Whirlpool [http://en.wikipedia.org/wiki/Whirlpool_(cryptography)]
	 *
	 * Both of them have an output of 512 bits. Even if one of them is broken in the future, you have to break both of them
	 * at the same time due to being hashed separately and then XORed to mix their results equally.
	 *
	 * @param string $salt        	
	 * @param string $password        	
	 *
	 * @return string[128] hex 512-bit hash
	 */
	public final function hash($salt, $password) {
		return bin2hex ( hash ( "sha512", $password . $salt, true ) ^ hash ( "whirlpool", $salt . $password, true ) );
	}
	/**
	 *
	 * @return \pocketmine\Server
	 */
	public final function getServer() {
		return $this->server;
	}
	/**
	 *
	 * @return \ifteam\UUIDAuth\auth\AuthDataProvider
	 */
	public final function getAuthProvider() {
		return $this->provider;
	}
	/**
	 *
	 * @return \ifteam\UUIDAuth\UUIDAuth
	 */
	public final function getPlugin() {
		return $this->plugin;
	}
}

?>