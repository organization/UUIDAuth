<?php

namespace ifteam\UUIDAuth\task;

use pocketmine\scheduler\Task;
use ifteam\UUIDAuth\auth\AuthDataLoader;

class AutoUnloadTask extends Task {
	protected $owner;
	public function __construct(AuthDataLoader $owner) {
		$this->owner = $owner;
	}
	public function onRun($currentTick) {
		$this->owner->unloadAuth ();
	}
}
?>