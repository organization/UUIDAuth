<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\RegisterRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class RegisterRequestProcess extends ProcessBase {
	public function process(RegisterRequestPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::REGISTER_REQUEST;
	}
}

?>