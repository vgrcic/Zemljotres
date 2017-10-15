<?php

	abstract class Repository {

		private static $connection;

		public function connect() {
			if (self::$connection == null) {
				$config = parse_ini_file("../config.ini");
				self::$connection = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			}
			return self::$connection;
		}

	}

?>