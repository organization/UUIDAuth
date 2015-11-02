<?php

namespace ifteam\UUIDAuth\auth;

use pocketmine\Server;
use ifteam\UUIDAuth;
use ifteam\UUIDAuth\auth\network\NormalAuth;

class AuthManager {
	private $server;
	private $provider;
	private $plugin;
	private $auths = [ ];
	public function __construct(AuthDataProvider $provider, Server $server, UUIDAuth $plugin) {
		$this->server = $server;
		$this->provider = $provider;
		$this->plugin = $plugin;
	}
	
	/**
	 *
	 * @param string $name        	
	 *
	 * @return null|Auth
	 */
	public function getAuthentication($name) {
		if (isset ( $this->auths [$name] ))
			return $this->auths [$name];
		return \null;
	}
	public function registerAuthentication($class) {
		$this->auths [get_class ( $class )] = $class;
		
		return \true;
	}
	public function loadAuthentication($class) {
		if (isset ( $this->auths [get_class ( $class )] )) {
			$auth = $this->auths [get_class ( $class )];
			if ($auth instanceof AuthBase) {
				$auth->init ( $this->provider, $this->server );
				return true;
			}
		}
		return false;
	}
	public function loadAuthentications() {
		foreach ( $this->auths as $auth )
			$this->loadAuthentication ( $auth );
	}
	public function registerAuthentications() {
		$normalAuth = new NormalAuth ();
		$normalAuth->init ( $this->provider, $this->server, $this->plugin );
		$normalAuth->onLoad ();
		$this->registerAuthentication ( $normalAuth->getAuthBase () );
		
		// TODO Email SMS SNS or etc...
	}
	/**
	 *
	 * @return Auth[]
	 */
	public function getAuths() {
		return $this->auths;
	}
}

?>