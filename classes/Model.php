<?php

	class Model {

		protected $attributes = [];

		public function __construct($attributes = []) {

			foreach($attributes as $key => $value) {
				$this -> setAttribute($key, $value);
			}

		}

		public function __get($key) {

			if (array_key_exists($key, $this->attributes))
				return $this->attributes[$key];

			return null;
		}

		public function __set($key, $value) {

			$this -> setAttribute($key, $value);

		}

		public function __isset($key) {

			return isset($this->attributes[$key]);

		}

		public function setAttribute($key, $value) {

			if (in_array($key, static::$fillable) ||
				in_array($key, array_keys($this->getRelationships()))) {
				$this->attributes[$key] = $value;
			}

		}

		public function getFillable() {
			return static::$fillable;
		}

		public function getTable() {
			return static::$table;
		}

		public function getAttributes() {
			return $this->attributes;
		}

		public function getRelationships() {
			if (isset(static::$relationships))
				return static::$relationships;
			return [];
		}

		public function hasRelationships() {
			return isset(static::$relationships);
		}

		public function getEagerLoads() {
			return static::$eagerLoads;
		}

	}

?>