<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\OnlinePacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class OnlineProcess extends ProcessBase {
	public function process(OnlinePacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::ONLINE;
	}
}

?>