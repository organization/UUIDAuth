<?php

namespace ifteam\UUIDAuth\event;

use ifteam\UUIDAuth;
use pocketmine\event\plugin\PluginEvent;

abstract class UUIDAuthEvent extends PluginEvent {
	/**
	 *
	 * @param UUIDAuth $plugin        	
	 */
	public function __construct(UUIDAuth $plugin) {
		parent::__construct ( $plugin );
	}
}

?>