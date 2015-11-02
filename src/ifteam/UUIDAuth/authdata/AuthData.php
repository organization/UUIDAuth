<?php

namespace ifteam\UUIDAuth\authdata;

use pocketmine\utils\Config;

class AuthData {
	private $userName;
	private $dataFolder;
	public $data;
	public function __construct($userName, $dataFolder) {
		$userName = strtolower ( $userName );
		
		$this->userName = $userName;
		$this->dataFolder = $dataFolder . substr ( $userName, 0, 1 ) . "/";
		
		if (! file_exists ( $this->dataFolder ))
			@mkdir ( $this->dataFolder );
		
		$this->load ();
	}
	public function load() {
		$this->data = (new Config ( $this->dataFolder . $this->userName . ".json", Config::JSON, [ ] ))->getAll ();
	}
	public function save($async = false) {
		$data = new Config ( $this->dataFolder . $this->userName . ".json", Config::JSON );
		$data->setAll ( $this->data );
		$data->save ( $async );
	}
	public function setIp($ip) {
		$this->data ["ip"] = $ip;
	}
	public function setPassWordHash($hash) {
		$this->data ["password"] = $hash;
	}
	public function setUUID($uuid) {
		$this->data ["uuid"] = $uuid;
	}
	public function getIp() {
		return $this->data ["ip"];
	}
	public function getPassWordHash() {
		return $this->data ["password"];
	}
	public function getUUID() {
		return $this->data ["uuid"];
	}
}

?>