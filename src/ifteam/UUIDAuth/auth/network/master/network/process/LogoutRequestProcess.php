<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\LogoutRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class LogoutRequestProcess extends ProcessBase {
	public function process(LogoutRequestPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::LOGOUT_REQUEST;
	}
}

?>