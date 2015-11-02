<?php

namespace ifteam\UUIDAuth\auth\network;

use ifteam\UUIDAuth\auth\AuthLoader;
use ifteam\UUIDAuth\auth\network\slave\SlaveAuth;
use ifteam\UUIDAuth\auth\network\master\MasterAuth;

class NormalAuth extends AuthLoader {
	private $useCustomPacket;
	private $serverMode;
	public function onLoad() {
		$this->useCustomPacket = $this->getPlugin ()->getConfig ()->get ( "usecustompacket", null );
		$this->serverMode = $this->getPlugin ()->getConfig ()->get ( "servermode", null );
	}
	public function getAuthBase() {
		return ($this->useCustomPacket == true and $this->serverMode == "slave") ? new SlaveAuth () : new MasterAuth ();
	}
}

?>