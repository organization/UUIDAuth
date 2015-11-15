<?php

namespace ifteam\UUIDAuth\auth;

use pocketmine\Server;
use ifteam\UUIDAuth;
use ifteam\UUIDAuth\auth\network\NormalAuth;

class AuthManager {
	private static $instance = null;
	/** @var \pocketmine\Server */
	private $server;
	/** @var \ifteam\UUIDAuth\auth\AuthDataProvider */
	private $provider;
	/** @var \ifteam\UUIDAuth\UUIDAuth */
	private $plugin;
	private $auths = [ ];
	public function __construct(AuthDataProvider $provider, Server $server, UUIDAuth $plugin) {
		if (self::$instance === null)
			self::$instance = $this;
		
		$this->server = $server;
		$this->provider = $provider;
		$this->plugin = $plugin;
		
		$this->registerAuthentications ();
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
		$this->auths [strtolower ( get_class ( $class ) )] = $class;
		
		return \true;
	}
	public function loadAuthentication($class) {
		if (isset ( $this->auths [strtolower ( get_class ( $class ) )] )) {
			$auth = $this->auths [strtolower ( get_class ( $class ) )];
			if ($auth instanceof AuthBase) {
				$auth->init ( $this->provider, $this->server, $this->plugin );
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
		
		$this->loadAuthentication ( $normalAuth->getAuthBase () );
		$this->registerAuthentication ( $normalAuth->getAuthBase () );
		// TODO Email SMS SNS or etc...
	}
	/**
	 *
	 * @return null|Auth
	 */
	public function getAuthBase() {
		$authentication = $this->plugin->getConfig ()->get ( "authentication", null );
		return $this->getAuthentication ( strtolower ( $authentication ) );
	}
	/**
	 *
	 * @return Auth[]
	 */
	public function getAuths() {
		return $this->auths;
	}
	/**
	 *
	 * @return AuthManager
	 */
	public static function getInstance() {
		return self::$instance;
	}
}

?>