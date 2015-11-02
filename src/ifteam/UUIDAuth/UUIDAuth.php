<?php

namespace ifteam\UUIDAuth;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use ifteam\UUIDAuth\database\PluginData;
use ifteam\UUIDAuth\listener\EventListener;
use ifteam\UUIDAuth\task\AutoSaveTask;
use ifteam\UUIDAuth\importer\SimpleAuth\SimpleAuthImporter;
use ifteam\UUIDAuth\auth\AuthDataLoader;
use ifteam\UUIDAuth\auth\AuthDataProvider;

class UUIDAuth extends PluginBase implements Listener {
	private $database;
	private $eventListener;
	private $authDataLoader;
	private $authDataProvider;
	public $m_version = 1;
	/**
	 * Called when the plugin is enabled
	 *
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable() {
		$this->database = new PluginData ( $this );
		
		$this->authDataLoader = new AuthDataLoader ( $this );
		$this->authDataProvider = new AuthDataProvider ( $this );
		$this->eventListener = new EventListener ( $this );
		
		$this->saveResource ( "config.yml", false );
		$this->database->initMessage ();
		
		$this->database->registerCommand ( $this->database->get ( "login" ), "UUIDAuth.login", $this->database->get ( "login-help" ), "/" . $this->database->get ( "login" ) );
		$this->database->registerCommand ( $this->database->get ( "logout" ), "UUIDAuth.logout", $this->database->get ( "logout-help" ), "/" . $this->database->get ( "logout" ) );
		$this->database->registerCommand ( $this->database->get ( "register" ), "UUIDAuth.register", $this->database->get ( "register-help" ), "/" . $this->database->get ( "register" ) );
		$this->database->registerCommand ( $this->database->get ( "unregister" ), "UUIDAuth.unregister", $this->database->get ( "unregister-help" ), "/" . $this->database->get ( "unregister" ) );
		$this->database->registerCommand ( $this->database->get ( "otp" ), "UUIDAuth.otp", $this->database->get ( "otp-help" ), "/" . $this->database->get ( "otp" ) );
		$this->database->registerCommand ( "uuidauth", "UUIDAuth.manage", $this->database->get ( "manage-help" ), "/uuidauth" );
		
		if (file_exists ( $this->getDataFolder () . "SimpleAuth/players" ))
			SimpleAuthImporter::getSimpleAuthData ( $this );
		
		$this->getServer ()->getPluginManager ()->registerEvents ( $this, $this );
		$this->getServer ()->getScheduler ()->scheduleRepeatingTask ( new AutoSaveTask ( $this ), 12000 );
	}
	/**
	 * Called when the plugin is disabled Use this to free open things and finish actions
	 *
	 * @see \pocketmine\plugin\PluginBase::onDisable()
	 */
	public function onDisable() {
		$this->save ();
	}
	/**
	 * Save plug-in configs
	 *
	 * @param string $async        	
	 */
	public function save($async = false) {
		$this->database->save ( $async );
	}
	/**
	 * Handles the received command
	 *
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) {
		return $this->eventListener->onCommand ( $sender, $command, $label, $args );
	}
	/**
	 * Return Plug-in Database
	 */
	public function getDataBase() {
		return $this->database;
	}
	/**
	 * Return Plug-in Event Listener
	 */
	public function getEventListener() {
		return $this->eventListener;
	}
	public function getAuthLoader() {
		return $this->authDataLoader;
	}
	public function getAuthProvider() {
		return $this->authDataProvider;
	}
}

?>