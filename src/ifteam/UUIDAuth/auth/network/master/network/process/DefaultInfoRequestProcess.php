<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\DefaultPacketRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class DefaultInfoRequestProcess extends ProcessBase {
	public function process(DefaultPacketRequestPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::DEFAULT_INFO_REQUEST;
	}
}

?>