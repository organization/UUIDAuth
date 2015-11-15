<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\LoginRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class LoginRequestProcess extends ProcessBase {
	public function process(LoginRequestPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::LOGIN_REQUEST;
	}
}

?>