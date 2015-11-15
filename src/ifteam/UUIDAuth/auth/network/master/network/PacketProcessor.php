<?php

namespace ifteam\UUIDAuth\auth\network\master\network;

use ifteam\UUIDAuth\auth\network\protocol\DataPacket;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol as Info;
use ifteam\UUIDAuth\auth\network\master\network\process\AckProcess;
use ifteam\UUIDAuth\auth\base\AuthBase;
use ifteam\UUIDAuth\auth\network\master\network\process\CheckAccountProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\DefaultInfoRequestProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\LoginRequestProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\LogoutRequestProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\OnlineProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\RegisterRequestProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\UnregisterRequestProcess;
use ifteam\UUIDAuth\auth\network\master\network\process\ProcessBase;

class PacketProcessor {
	private static $instance = null;
	public $auth;
	public $processor = [ ];
	public function __construct(AuthBase $auth) {
		if (self::$instance === null)
			self::$instance = $this;
		
		$this->auth = $auth;
		$this->registerProcessors ();
	}
	public function registerProcessors() {
		$this->processor [AckProcess::getPid ()] = new AckProcess ( $this->auth );
		$this->processor [CheckAccountProcess::getPid ()] = new CheckAccountProcess ( $this->auth );
		$this->processor [DefaultInfoRequestProcess::getPid ()] = new DefaultInfoRequestProcess ( $this->auth );
		$this->processor [LoginRequestProcess::getPid ()] = new LoginRequestProcess ( $this->auth );
		$this->processor [LogoutRequestProcess::getPid ()] = new LogoutRequestProcess ( $this->auth );
		$this->processor [OnlineProcess::getPid ()] = new OnlineProcess ( $this->auth );
		$this->processor [RegisterRequestProcess::getPid ()] = new RegisterRequestProcess ( $this->auth );
		$this->processor [UnregisterRequestProcess::getPid ()] = new UnregisterRequestProcess ( $this->auth );
	}
	/**
	 *
	 * @param int $pid        	
	 * @return ProcessBase
	 */
	public function getProcessor($pid) {
		if (! isset ( $this->processor [$pid] ))
			return null;
		return $this->processor [$pid];
	}
	public function process(DataPacket $packet) {
		switch ($packet->pid) {
			case Info::ONLINE :
			case Info::ACK :
			case Info::CHECK_ACCOUNT :
			case Info::DEFAULT_INFO_REQUEST :
			case Info::LOGIN_REQUEST :
			case Info::LOGOUT_REQUEST :
			case Info::REGISTER_REQUEST :
			case Info::UNREGISTER_REQUEST :
				$this->getProcessor ( $packet->pid )->process ( $packet );
				break;
		}
	}
	public static function getInstance() {
		return self::$instance;
	}
}

?>