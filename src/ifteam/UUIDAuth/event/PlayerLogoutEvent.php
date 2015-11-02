<?php

namespace ifteam\UUIDAuth\event;

use ifteam\UUIDAuth;
use pocketmine\IPlayer;

class PlayerLogoutEvent extends UUIDAuthEvent {
	public static $handlerList = null;
	
	/**
	 *
	 * @var IPlayer
	 */
	private $player;
	
	/**
	 *
	 * @param UUIDAuth $plugin        	
	 * @param IPlayer $player        	
	 */
	public function __construct(UUIDAuth $plugin, IPlayer $player) {
		$this->player = $player;
		parent::__construct ( $plugin );
	}
	
	/**
	 *
	 * @return IPlayer
	 */
	public function getPlayer() {
		return $this->player;
	}
}

?>