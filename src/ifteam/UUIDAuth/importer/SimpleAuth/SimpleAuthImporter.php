<?php

namespace ifteam\UUIDAuth\importer\SimpleAuth;

use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use ifteam\UUIDAuth\database\PluginData;

class SimpleAuthImporter {
	public static function getSimpleAuthData(Plugin $plugin, $db = null) {
		if (! file_exists ( $plugin->getDataFolder () . "SimpleAuth/players" ))
			return;
		$config = (new Config ( $plugin->getDataFolder () . "SimpleAuth/config.yml", Config::YAML ))->getAll ();
		$provider = $config ["dataProvider"];
		switch (strtolower ( $provider )) {
			case "yaml" :
				$plugin->getLogger ()->debug ( "Using YAML data provider" );
				$provider = new YAMLDataProvider ( $plugin );
				break;
			case "sqlite3" :
				$plugin->getLogger ()->debug ( "Using SQLite3 data provider" );
				$provider = new SQLite3DataProvider ( $plugin );
				break;
			case "mysql" :
				$plugin->getLogger ()->debug ( "Using MySQL data provider" );
				$provider = new MySQLDataProvider ( $plugin );
				break;
			case "none" :
			default :
				$provider = new DummyDataProvider ( $plugin );
				break;
		}
		$folderList = self::getFolderList ( $plugin->getDataFolder () . "SimpleAuth/players", "folder" );
		foreach ( $folderList as $alphabet ) {
			$ymlList = self::getFolderList ( $plugin->getDataFolder () . "SimpleAuth/players/" . $alphabet, "file" );
			foreach ( $ymlList as $ymlName ) {
				$yml = (new Config ( $plugin->getDataFolder () . "SimpleAuth/players/" . $alphabet . "/" . $ymlName, Config::YAML ))->getAll ();
				$name = explode ( ".", $ymlName ) [0];
				if ($db instanceof PluginData)
					$db->addAuthReady ( mb_convert_encoding ( $name, "UTF-8" ), $yml ["hash"] );
			}
		}
		self::rmdirAll ( $plugin->getDataFolder () . "SimpleAuth" );
	}
	/**
	 *
	 * It gets a list of folders or files
	 *
	 * @param string $rootDir        	
	 * @param string $filter
	 *        	= "folder" || "file" || null
	 *        	
	 * @return array $rList
	 */
	public function getFolderList($rootDir, $filter = "") {
		$handler = opendir ( $rootDir );
		$rList = array ();
		$fCounter = 0;
		while ( $file = readdir ( $handler ) ) {
			if ($file != '.' && $file != '..') {
				if ($filter == "folder") {
					if (is_dir ( $rootDir . "/" . $file )) {
						$rList [$fCounter ++] = $file;
					}
				} else if ($filter == "file") {
					if (! is_dir ( $rootDir . "/" . $file )) {
						$rList [$fCounter ++] = $file;
					}
				} else {
					$rList [$fCounter ++] = $file;
				}
			}
		}
		closedir ( $handler );
		return $rList;
	}
	/**
	 *
	 * Delete the folder to the subfolders
	 *
	 * @param string $dir        	
	 *
	 */
	public function rmdirAll($dir) {
		$dirs = dir ( $dir );
		while ( false !== ($entry = $dirs->read ()) ) {
			if (($entry != '.') && ($entry != '..')) {
				if (is_dir ( $dir . '/' . $entry )) {
					self::rmdirAll ( $dir . '/' . $entry );
				} else {
					@unlink ( $dir . '/' . $entry );
				}
			}
		}
		$dirs->close ();
		@rmdir ( $dir );
	}
}

?>