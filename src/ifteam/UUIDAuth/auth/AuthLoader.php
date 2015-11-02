<?php

namespace ifteam\UUIDAuth\auth;

use pocketmine\Server;
use ifteam\UUIDAuth\UUIDAuth;

abstract class AuthLoader {
	/** @var \pocketmine\Server */
	private $server;
	/** @var \ifteam\UUIDAuth\auth\AuthDataProvider */
	private $provider;
	/** @var \ifteam\UUIDAuth\UUIDAuth */
	private $plugin;
	public function init(AuthDataProvider $provider, Server $server, UUIDAuth $plugin) {
		$this->provider = $provider;
		$this->server = $server;
		$this->plugin = $plugin;
	}
	public function onLoad() {
	}
	public function getAuthBase() {
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
	public final function getProvider() {
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