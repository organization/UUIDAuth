<?php

namespace ifteam\UUIDAuth\auth\base;

use pocketmine\Server;
use ifteam\UUIDAuth\UUIDAuth;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use ifteam\UUIDAuth\auth\Auth;

abstract class AuthBase implements Auth {
	/** @var \ifteam\UUIDAuth\auth\AuthDataProvider */
	private $provider;
	/** @var \pocketmine\Server */
	private $server;
	/** @var \ifteam\UUIDAuth\UUIDAuth  */
	private $plugin;
	private $commands;
	public final function init(AuthDataProvider $provider, Server $server, UUIDAuth $plugin) {
		$this->provider = $provider;
		$this->server = $server;
		$this->plugin = $plugin;
		$this->onLoad ();
	}
	public function onLoad() {
	}
	public function authenticatePlayer(Player $player) {
	}
	public function deauthenticatePlayer(Player $player) {
	}
	public function cueAuthenticatePlayer(Player $player) {
	}
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
		if (isset ( $this->commands [$command->getName ()] )) {
			$command = $this->commands [$command->getName ()];
			
			if ($command instanceof \ifteam\UUIDAuth\auth\base\commands\Command) {
				$command->onCommand ( $player, $command, $label, $args );
			}
		}
	}
	/**
	 *
	 * @param \ifteam\UUIDAuth\auth\base\commands\Command $command        	
	 */
	public function registerCommand(\ifteam\UUIDAuth\auth\base\commands\Command $command) {
		$this->commands [$command->getCommand ()->getName ()] = $command;
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