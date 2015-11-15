<?php

namespace ifteam\UUIDAuth\auth\network\protocol;

interface UUIDAuthProtocol {
	/**
	 * Actual UUIDAuth protocol version
	 */
	const CURRENT_PROTOCOL = 1;
	
	/* PLUG-IN PROTOCOL */
	const ONLINE = 0x00;
	const ACK = 0x01;
	
	/* GET INFORMATION PROTOCOL */
	const CHECK_ACCOUNT = 0x10;
	const DEFAULT_INFO_REQUEST = 0x11;
	
	/* LOGIN & LOGOUT PROTOCOL */
	const LOGIN_REQUEST = 0x20;
	const LOGOUT_REQUEST = 0x21;
	
	/* APPLY DATA PROTOCOL */
	const REGISTER_REQUEST = 0x30;
	const UNREGISTER_REQUEST = 0x31;
}

?>