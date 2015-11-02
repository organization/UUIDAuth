<?php

namespace ifteam\UUIDAuth\auth;

use pocketmine\Server;

abstract class AuthBase extends Auth {
	/** @var \ifteam\UUIDAuth\auth\AuthDataProvider */
	private $provider;
	/** @var \pocketmine\Server */
	private $server;
	public final function init(AuthDataProvider $provider, Server $server) {
		$this->provider = $provider;
		$this->server = $server;
	}
	public function authenticatePlayer($player) {
	}
	public function deauthenticatePlayer($player) {
	}
	public function cueAuthenticatePlayer($player) {
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
	 * @return Server
	 */
	public final function getServer() {
		return $this->server;
	}
	public final function getAuthProvider() {
		return $this->provider;
	}
}

?>