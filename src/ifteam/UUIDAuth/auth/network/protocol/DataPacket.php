<?php

namespace ifteam\UUIDAuth\auth\network\protocol;

abstract class DataPacket {
	public $passcode;
	public $pid;
	public $needACK;
	public $identifier;
	public $encodedSub = null;
	public function decode(array $array) {
		/* $packet [0] = passcode */
		/* $packet [1] = pid */
		/* $packet [2] = needACK */
		/* $packet [3] = identifier */
		/* $packet [...] = etc */
		$this->passcode = array_shift ( $array );
		$this->pid = array_shift ( $array );
		$this->needACK = array_shift ( $array );
		$this->identifier = array_shift ( $array );
		
		$this->decodeSub ( $array );
	}
	public function decodeString($string) {
		$this->decode ( ( array ) json_decode ( $string, true ) );
	}
	public function encode() {
		$encode = [ 
				$this->passcode,
				$this->pid,
				$this->needACK,
				null 
		];
		
		$encodeSub = $this->encodeSub ();
		
		if ($this->needACK) {
			$encode [3] = $this->crc32 ( json_encode ( $encodeSub ) );
		}
		
		$encoded = json_encode ( array_merge ( $encode, $encodeSub ) );
		
		if ($this->needACK) {
			// $encode [3]
			// $encoded;
			
			// TODO checkACK and process
		}
		
		return $encoded;
	}
	public function decodeSub(array $array) {
	}
	/**
	 *
	 * @return array
	 */
	public function encodeSub() {
	}
	public final function crc32($string) {
		return bin2hex ( hash ( "crc32", $string, true ) );
	}
}

?>