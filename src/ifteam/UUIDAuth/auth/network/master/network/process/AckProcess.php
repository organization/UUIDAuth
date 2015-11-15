<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\AckPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class AckProcess extends ProcessBase {
	public function process(AckPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::ACK;
	}
}

?>