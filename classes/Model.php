<?php

	class Model {

		protected $fillable = [];

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

			if (in_array($key, $this->fillable)) {
				$this->attributes[$key] = $value;
			}

		}

		public function getFillable() {
			return $this->fillable;
		}

	}

?>