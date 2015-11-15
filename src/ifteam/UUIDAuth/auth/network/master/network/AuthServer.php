<?php

namespace ifteam\UUIDAuth\auth\network\master;

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\plugin\Plugin;
use ifteam\CustomPacket\event\CustomPacketReceiveEvent;
use ifteam\UUIDAuth\auth\network\protocol\UUIDAuthProtocol as Info;
use ifteam\UUIDAuth\auth\network\protocol\OnlinePacket;
use ifteam\UUIDAuth\auth\network\protocol\AckPacket;
use ifteam\UUIDAuth\auth\network\protocol\CheckAccountPacket;
use ifteam\UUIDAuth\auth\network\protocol\DefaultPacketRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\LoginRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\LogoutRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\RegisterRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\UnregisterRequestPacket;
use ifteam\UUIDAuth\auth\network\protocol\DataPacket;

class AuthServer implements Listener {
	private static $instance = null;
	private $server;
	private $plugin;
	private $customPacket;
	public function __construct(Plugin $plugin) {
		$this->server = Server::getInstance ();
		$this->plugin = $plugin;
		$this->customPacket = $this->server->getPluginManager ()->getPlugin ( "CustomPacket" );
		
		if (! $this->customPacket instanceof \ifteam\CustomPacket\MainLoader)
			return;
		
		$this->server->getPluginManager ()->registerEvents ( $this, $plugin );
		
		if (self::$instance === null)
			self::$instance = $this;
	}
	public function onCustomPacketReceiveEvent(CustomPacketReceiveEvent $event) {
		$packet = ( array ) json_decode ( $ev->getPacket ()->data, true );
		
		if (! is_array ( $packet ) or $packet [0] != $this->plugin->getConfig ()->get ( "passcode", false ))
			return;
		if (! isset ( $packet [4] ))
			return;
			
			/* $packet [0] = passcode */
			/* $packet [1] = pid */
			/* $packet [2] = needACK */
			/* $packet [3] = identifier */
			/* $packet [...] = etc */
		$dataPacket = $this->getPacket ( $packet [1] );
		
		if (! $dataPacket instanceof DataPacket)
			return;
		
		$dataPacket->decode ( $packet );
		// TODO PROCESS PACKET
	}
	/**
	 *
	 * @param unknown $pid        	
	 */
	public function getPacket($pid) {
		$packet = null;
		
		switch ($pid) {
			case Info::ONLINE :
				$packet = new OnlinePacket ();
				break;
			case Info::ACK :
				$packet = new AckPacket ();
				break;
			case Info::CHECK_ACCOUNT :
				$packet = new CheckAccountPacket ();
				break;
			case Info::DEFAULT_INFO_REQUEST :
				$packet = new DefaultPacketRequestPacket ();
				break;
			case Info::LOGIN_REQUEST :
				$packet = new LoginRequestPacket ();
				break;
			case Info::LOGOUT_REQUEST :
				$packet = new LogoutRequestPacket ();
				break;
			case Info::REGISTER_REQUEST :
				$packet = new RegisterRequestPacket ();
				break;
			case Info::UNREGISTER_REQUEST :
				$packet = new UnregisterRequestPacket ();
				break;
		}
		
		return $packet;
	}
}

?>