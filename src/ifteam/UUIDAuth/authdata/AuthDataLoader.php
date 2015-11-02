<?php

namespace ifteam\UUIDAuth\auth;

use pocketmine\Player;
use pocketmine\Server;
use ifteam\UUIDAuth\UUIDAuth;
use ifteam\UUIDAuth\task\AutoUnloadTask;

class AuthLoader {
	private static $instance = null;
	/**
	 *
	 * @var Users prefix data
	 */
	private $users = [ ];
	/**
	 *
	 * @var UUIDAuth
	 */
	private $plugin;
	/**
	 *
	 * @var Server
	 */
	private $server;
	public function __construct(UUIDAuth $plugin) {
		if (self::$instance == null)
			self::$instance = $this;
		
		$this->server = Server::getInstance ();
		$this->plugin = $plugin;
		
		$this->server->getScheduler ()->scheduleRepeatingTask ( new AutoUnloadTask ( $this ), 12000 );
	}
	/**
	 * Create a default setting
	 *
	 * @param string $userName        	
	 */
	public function loadAuth($userName) {
		$userName = strtolower ( $userName );
		$alpha = substr ( $userName, 0, 1 );
		
		if (isset ( $this->users [$userName] ))
			return $this->users [$userName];
		
		if (! file_exists ( $this->plugin->getDataFolder () . "player/" ))
			@mkdir ( $this->plugin->getDataFolder () . "player/" );
		
		return $this->users [$userName] = new AuthData ( $userName, $this->plugin->getDataFolder () . "player/" );
	}
	public function unloadAuth($userName = null) {
		if ($userName === null) {
			foreach ( $this->users as $userName => $authData ) {
				if ($this->users [$userName] instanceof AuthData)
					$this->users [$userName]->save ( true );
				unset ( $this->users [$userName] );
			}
			return true;
		}
		
		$userName = strtolower ( $userName );
		if (! isset ( $this->users [$userName] ))
			return false;
		if ($this->users [$userName] instanceof AuthData) {
			$this->users [$userName]->save ( true );
		}
		unset ( $this->users [$userName] );
		return true;
	}
	/**
	 * Get Auth Data
	 *
	 * @param Player $player        	
	 * @return AuthData
	 */
	public function getAuth(Player $player) {
		$userName = strtolower ( $player->getName () );
		if (! isset ( $this->users [$userName] ))
			$this->loadAuth ( $userName );
		return $this->users [$userName];
	}
	/**
	 * Get Auth Data
	 *
	 * @param string $player        	
	 * @return AuthData
	 */
	public function getAuthToName($name) {
		$userName = strtolower ( $name );
		if (! isset ( $this->users [$userName] ))
			$this->loadAuth ( $userName );
		return $this->users [$userName];
	}
	public function save($async = false) {
		foreach ( $this->users as $userName => $authData )
			if ($authData instanceof AuthData)
				$authData->save ( $async );
	}
	/**
	 *
	 * @return AreaLoader
	 */
	public static function getInstance() {
		return static::$instance;
	}
}

?>