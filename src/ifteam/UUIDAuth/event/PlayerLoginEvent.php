<?php

namespace ifteam\UUIDAuth\event;

use ifteam\UUIDAuth;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerLoginEvent extends UUIDAuthEvent implements Cancellable {
	public static $handlerList = null;
	
	/**
	 *
	 * @var Player
	 */
	private $player;
	
	/**
	 *
	 * @param UUIDAuth $plugin        	
	 * @param Player $player        	
	 */
	public function __construct(UUIDAuth $plugin, Player $player) {
		$this->player = $player;
		parent::__construct ( $plugin );
	}
	
	/**
	 *
	 * @return Player
	 */
	public function getPlayer() {
		return $this->player;
	}
}

?>