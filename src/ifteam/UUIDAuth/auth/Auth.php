<?php

namespace ifteam\UUIDAuth\auth;

interface Auth {
	public function authenticatePlayer($player);
	public function deauthenticatePlayer($player);
	public function cueAuthenticatePlayer($player);
}
?>