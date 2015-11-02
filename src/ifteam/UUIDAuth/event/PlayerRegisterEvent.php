<?php

namespace ifteam\UUIDAuth\event;

use ifteam\UUIDAuth;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerRegisterEvent extends UUIDAuthEvent implements Cancellable {
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
	 * @return IPlayer
	 */
	public function getPlayer() {
		return $this->player;
	}
}

?>