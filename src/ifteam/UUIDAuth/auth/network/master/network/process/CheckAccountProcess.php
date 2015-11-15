<?php

namespace ifteam\UUIDAuth\auth\network\master\network\process;

use ifteam\UUIDAuth\auth\network\protocol\CheckAccountPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol;

class CheckAccountProcess extends ProcessBase {
	public function process(CheckAccountPacket $packet) {
	}
	public static function getPid() {
		return UUIDAuthProtocol::CHECK_ACCOUNT;
	}
}

?>