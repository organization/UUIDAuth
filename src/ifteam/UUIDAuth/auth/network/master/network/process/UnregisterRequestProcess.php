<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\UnregisterRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class UnregisterRequestProcess extends ProcessBase {
	public function process(UnregisterRequestPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::UNREGISTER_REQUEST;
	}
}

?>