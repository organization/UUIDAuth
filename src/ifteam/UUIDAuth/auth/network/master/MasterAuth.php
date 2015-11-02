<?php

namespace ifteam\UUIDAuth\auth\network\master;

use ifteam\UUIDAuth\auth\AuthBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use ifteam\UUIDAuth\authdata\AuthData;

class MasterAuth extends AuthBase implements Listener {
	public $needAuth = [ ];
	/**
	 * Prevent brute forcing
	 */
	public $wrongauth = [ ];
	public function onLoad() {
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this->getPlugin () );
	}
	public function authenticatePlayer($player) {
	}
	public function deauthenticatePlayer($player) {
	}
	public function cueAuthenticatePlayer($player) {
	}
	public function onCommand(CommandSender $player, Command $command, $label, array $args) {
		switch (strtolower ( $command->getName () )) {
			case $this->get ( "login" ) :
				if (! isset ( $this->needAuth [$player->getName ()] )) {
					$this->message ( $player, $this->get ( "already-logined" ) );
					return true;
				}
				if ($this->db->getEmail ( $player )) {
					if (! isset ( $args [0] )) {
						$this->loginMessage ( $player );
						return true;
					}
					$email = $this->db->getEmail ( $player );
					if ($email != false) {
						$data = $this->db->getUserData ( $email );
						if ($data == false) {
							$this->message ( $player, $this->get ( "this-account-cant-use" ) );
							return true;
						}
						if ($data ["password"] != $args [0]) {
							$this->alert ( $player, $this->get ( "login-is-failed" ) );
							if ($player instanceof Player) {
								if (isset ( $this->wrongauth [$player->getAddress ()] )) {
									$this->wrongauth [$player->getAddress ()] ++;
									if ($this->wrongauth [$player->getAddress ()] >= 7)
										$this->getServer ()->blockAddress ( $player->getAddress (), 400 );
									$player->kick ( $this->get ( "banned-brute-force" ) );
								} else {
									$this->wrongauth [$player->getAddress ()] = 1;
								}
							}
							$this->deauthenticatePlayer ( $player );
						} else {
							$this->authenticatePlayer ( $player );
						}
					}
				} else {
					$this->registerMessage ( $player );
					return true;
				}
				break;
			case $this->get ( "logout" ) :
				$auth = $this->getAuthProvider ()->getAuth ( $player->getName () );
				if ($auth instanceof AuthData)
					$auth->logout ( $player );
				$this->message ( $player, $this->get ( "logout-complete" ) );
				break;
			case $this->get ( "register" ) :
				// 가입 <이메일또는 코드> <원하는암호>
				if (! isset ( $this->needAuth [$player->getName ()] )) {
					$this->message ( $player, $this->get ( "already-logined" ) );
					return true;
				}
				if (! isset ( $args [1] )) {
					$this->message ( $player, $this->get ( "you-need-a-register" ) );
					return true;
				}
				$temp = $args;
				array_shift ( $temp );
				$password = implode ( " ", $temp );
				unset ( $temp );
				
				if (strlen ( $password ) > 50) {
					$this->message ( $player, $this->get ( "password-is-too-long" ) );
					return true;
				}
				if (! $this->db->checkAuthReady ( $player->getName () )) {
					if (strlen ( $password ) < $this->getConfig ()->get ( "minPasswordLength", 5 )) {
						$this->message ( $player, $this->get ( "too-short-password" ) );
						return true;
					}
				} else {
					if (! $this->db->checkAuthReadyKey ( $player->getName (), $password )) {
						$this->message ( $player, $this->get ( "wrong-password" ) );
						if ($player instanceof Player) {
							if (isset ( $this->wrongauth [strtolower ( $player->getAddress () )] )) {
								$this->wrongauth [$player->getAddress ()] ++;
								if ($this->wrongauth [$player->getAddress ()] >= 7)
									$this->getServer ()->blockAddress ( $player->getAddress (), 400 );
								$player->kick ( $this->get ( "banned-brute-force" ) );
							} else {
								$this->wrongauth [$player->getAddress ()] = 1;
							}
						}
						return true;
					}
				}
				if (is_numeric ( $args [0] )) {
					if (isset ( $this->authcode [$player->getName ()] )) {
						if ($this->authcode [$player->getName ()] ["authcode"] == $args [0]) {
							$password_hash = $this->hash ( strtolower ( $player->getName () ), $password );
							$result = $this->db->addUser ( $this->authcode [$player->getName ()] ["email"], $password_hash, $player->getAddress (), $player->getName () );
							if ($result) {
								$this->message ( $player, $this->get ( "register-complete" ) );
							} else {
								$this->message ( $player, $this->get ( "register-failed" ) );
								return true;
							}
							$this->authenticatePlayer ( $player );
							if ($this->db->checkAuthReady ( $player->getName () )) {
								$this->db->completeAuthReady ( $player->getName () );
							}
						} else {
							$this->message ( $player, $this->get ( "wrong-authcode" ) );
							if ($player instanceof Player) {
								if (isset ( $this->wrongauth [strtolower ( $player->getAddress () )] )) {
									$this->wrongauth [$player->getAddress ()] ++;
									if ($this->wrongauth [$player->getAddress ()] >= 7)
										$this->getServer ()->blockAddress ( $player->getAddress (), 400 );
									$player->kick ( $this->get ( "banned-brute-force" ) );
								} else {
									$this->wrongauth [$player->getAddress ()] = 1;
								}
							}
							$this->deauthenticatePlayer ( $player );
						}
						unset ( $this->authcode [$player->getName ()] );
					} else {
						$this->message ( $player, $this->get ( "authcode-doesnt-exist" ) );
						$this->deauthenticatePlayer ( $player );
					}
				} else {
					// 이메일!
					$e = explode ( '@', $args [0] );
					if (! isset ( $e [1] )) {
						$this->message ( $player, $this->get ( "wrong-email-type" ) );
						return true;
					}
					$e1 = explode ( '.', $e [1] );
					if (! isset ( $e1 [1] )) {
						$this->message ( $player, $this->get ( "wrong-email-type" ) );
						return true;
					}
					if ($this->db->checkUserData ( $args [0] ) != false) {
						$this->message ( $player, $this->get ( "already-registred" ) );
						return true;
					}
					$domainLock = $this->db->getLockDomain ();
					if ($domainLock != null) {
						if ($domainLock != $e [1]) {
							$msg = str_replace ( "%domain%", $domainLock, $this->get ( "you-can-use-email-domain" ) );
							$this->message ( $player, $msg );
							$this->message ( $player, $this->get ( "you-need-a-register" ) );
							return true;
						}
					}
					$playerName = $player->getName ();
					$authCode = $this->authCodeGenerator ( 6 );
					$nowTime = date ( "Y-m-d H:i:s" );
					$serverName = $this->getConfig ()->get ( "serverName", "" );
					if (isset ( $this->wrongauth [$player->getAddress ()] )) {
						$this->wrongauth [$player->getAddress ()] ++;
						if ($this->wrongauth [$player->getAddress ()] >= 7)
							$this->getServer ()->blockAddress ( $player->getAddress (), 400 );
						$player->kick ( $this->get ( "banned-brute-force" ) );
					} else {
						$this->wrongauth [$player->getAddress ()] = 1;
					}
					$task = new EmailSendTask ( $args [0], $playerName, $nowTime, $serverName, $authCode, $this->getConfig ()->getAll (), $this->getDataFolder () . "signform.html" );
					$this->getServer ()->getScheduler ()->scheduleAsyncTask ( $task );
					$this->authcode [$playerName] = [ 
							"authcode" => $authCode,
							"email" => $args [0] 
					];
					$this->message ( $player, $this->get ( "mail-has-been-sent" ) );
				}
				break;
			case $this->get ( "unregister" ) :
				$this->getAuthProvider ()->removeAuth ( $player->getName () );
				$this->message ( $player, $this->get ( "unregister-is-complete" ) );
				break;
			case "uuidauth" :
				if (! isset ( $args [0] )) {
					$this->message ( $player, $this->get ( "auth-help-setup" ) );
					$this->message ( $player, $this->get ( "auth-help-test" ) );
					$this->message ( $player, $this->get ( "auth-help-domain" ) );
					$this->message ( $player, $this->get ( "auth-help-length" ) );
					$this->message ( $player, $this->get ( "auth-help-whitelist" ) );
					return true;
				}
				switch (strtolower ( $args [0] )) {
					case "setup" :
						switch (strtolower ( $args [1] )) {
							case "mail" :
								if (! isset ( $args [2] )) {
									$this->message ( $player, $this->get ( "setup-help-mail" ) );
									break;
								}
								$this->getConfig ()->set ( "adminEmail", $args [2] );
								$this->message ( $player, $this->get ( "adminMail-setup-complete" ) );
								break;
							case "pass" :
								if (! isset ( $args [2] )) {
									$this->message ( $player, $this->get ( "setup-help-pass" ) );
									break;
								}
								$this->getConfig ()->set ( "adminEmailPassword", $args [2] );
								$this->message ( $player, $this->get ( "adminEmailPassword-setup-complete" ) );
								break;
							case "host" :
								if (! isset ( $args [2] )) {
									$this->message ( $player, $this->get ( "setup-help-host" ) );
									break;
								}
								$this->getConfig ()->set ( "adminEmailHost", $args [2] );
								$this->message ( $player, $this->get ( "adminEmailHost-setup-complete" ) );
								break;
							case "port" :
								if (! isset ( $args [2] )) {
									$this->message ( $player, $this->get ( "setup-help-port" ) );
									break;
								}
								$this->getConfig ()->set ( "adminEmailPort", $args [2] );
								$this->message ( $player, $this->get ( "adminEmailPort-setup-complete" ) );
								break;
							default :
								$this->message ( $player, $this->get ( "setup-help-mail" ) );
								$this->message ( $player, $this->get ( "setup-help-pass" ) );
								$this->message ( $player, $this->get ( "setup-help-host" ) );
								$this->message ( $player, $this->get ( "setup-help-port" ) );
								break;
						}
						$this->onActivateCheck ();
						break;
					case "test" :
						if ($this->getConfig ()->get ( "adminEmail", null ) == null) {
							$this->message ( $player, $this->get ( "adminMail-doesnt-exist" ) );
							$this->message ( $player, $this->get ( "setup-help-mail" ) );
							return true;
						}
						if ($this->getConfig ()->get ( "adminEmailHost", null ) == null) {
							$this->message ( $player, $this->get ( "adminEmailHost-doesnt-exist" ) );
							$this->message ( $player, $this->get ( "setup-help-pass" ) );
							return true;
						}
						if ($this->getConfig ()->get ( "adminEmailPort", null ) == null) {
							$this->message ( $player, $this->get ( "adminEmailPort-doesnt-exist" ) );
							$this->message ( $player, $this->get ( "setup-help-host" ) );
							return true;
						}
						if ($this->getConfig ()->get ( "adminEmailPassword", null ) == null) {
							$this->message ( $player, $this->get ( "adminEmailPassword-doesnt-exist" ) );
							$this->message ( $player, $this->get ( "setup-help-port" ) );
							return true;
						}
						$playerName = "CONSOLE";
						$authCode = $this->authCodeGenerator ( 6 );
						$nowTime = date ( "Y-m-d H:i:s" );
						$serverName = $this->getConfig ()->get ( "serverName", "" );
						$result = $this->sendRegisterMail ( $this->getConfig ()->get ( "adminEmail", null ), $playerName, $nowTime, $serverName, $authCode, true );
						if ($result)
							$this->message ( $player, $this->get ( "setup-complete" ) );
						if (! $result)
							$this->message ( $player, $this->get ( "setup-failed" ) );
						break;
					case "domain" :
						if (! isset ( $args [2] )) {
							$this->message ( $player, $this->get ( "auth-help-domain" ) );
							break;
						}
						$this->db->changeLockDomain ( $args [2] );
						break;
					case "length" :
						if (! isset ( $args [2] ) or ! is_numeric ( $args [2] )) {
							$this->message ( $player, $this->get ( "auth-help-length" ) );
							break;
						}
						$this->getConfig ()->set ( "minPasswordLength", $args [2] );
						break;
					case "whitelist" :
						$choose = $this->getConfig ()->get ( "minPasswordLength", false );
						$this->getConfig ()->set ( "minPasswordLength", ! $choose );
						(! $choose) ? $message = $this->get ( "whitelist-enabled" ) : $message = $this->get ( "whitelist-disabled" );
						$this->message ( $player, $message );
						break;
					default :
						$this->message ( $player, $this->get ( "auth-help-setup" ) );
						$this->message ( $player, $this->get ( "auth-help-test" ) );
						$this->message ( $player, $this->get ( "auth-help-domain" ) );
						$this->message ( $player, $this->get ( "auth-help-length" ) );
						$this->message ( $player, $this->get ( "auth-help-whitelist" ) );
						break;
					// 서버연동 /emailauth custompacket 으로 활성화처리
					case "custompacket" :
						$usecustompacket = $this->getConfig ()->get ( "usecustompacket", null );
						if ($usecustompacket == null) {
							$this->getConfig ()->set ( "usecustompacket", true );
							$this->message ( $player, $this->get ( "custompacket-enabled" ) );
							$this->message ( $player, $this->get ( "please-choose-mode" ) );
							return true;
						}
						if ($usecustompacket) {
							$this->getConfig ()->set ( "usecustompacket", false );
							$this->message ( $player, $this->get ( "custompacket-disabled" ) );
						} else {
							$this->getConfig ()->set ( "usecustompacket", true );
							$this->message ( $player, $this->get ( "custompacket-enabled" ) );
						}
						$this->message ( $player, $this->get ( "please-choose-mode" ) );
						break;
				}
				break;
		}
		return true;
	}
	public function registerMessage(CommandSender $player) {
		/**
		 *
		 * @todo 이메일 인증아님-> 로그인으로 설명변경
		 *      
		 */
		$this->message ( $player, $this->get ( "emailauth-notification" ) );
		$this->message ( $player, $this->get ( "you-need-a-register" ) );
		
		if ($domainLock != null) {
			$msg = str_replace ( "%domain%", $domainLock, $this->get ( "you-can-use-email-domain" ) );
			$this->message ( $player, $msg );
		}
	}
	public function getConfig() {
		return $this->getPlugin ()->getConfig ();
	}
	public function get($var) {
		return $this->getPlugin ()->getDataBase ()->get ( $var );
	}
	public function message(CommandSender $player, $text = "", $mark = null) {
		if ($mark == null)
			$mark = $this->getConfig ()->get ( "default-prefix", "" );
		$player->sendMessage ( TextFormat::DARK_AQUA . $mark . $text );
	}
	public function alert(CommandSender $player, $text = "", $mark = null) {
		if ($mark == null)
			$mark = $this->getConfig ()->get ( "default-prefix", "" );
		$player->sendMessage ( TextFormat::RED . $mark . $text );
	}
}

?>