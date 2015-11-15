<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\master\AuthServer;
use ifteam\UUIDAuth\UUIDAuth;
use ifteam\UUIDAuth\auth\AuthManager;
use ifteam\UUIDAuth\auth\base\AuthBase;
use ifteam\UUIDAuth\auth\network\protocol\DataPacket;

abstract class ProcessBase {
	private $authServer;
	private $authManager;
	private $authBase;
	public function __construct($auth) {
		$this->authServer = AuthServer::getInstance ();
		$this->authManager = AuthManager::getInstance ();
		$this->authBase = $auth;
	}
	public function process(DataPacket $packet) {
	}
	/**
	 *
	 * @return AuthServer
	 */
	public function getAuthServer() {
		return $this->authServer;
	}
	/**
	 *
	 * @return AuthManager
	 */
	public function getAuthManager() {
		return $this->authManager;
	}
	/**
	 *
	 * @return AuthBase
	 */
	public function getAuthBase() {
		return $this->authBase;
	}
}

?>