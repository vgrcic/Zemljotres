<?php

	abstract class Repository {

		private static $connection;

		private $builder;

		public function __construct() {
			$this -> builder = new QueryBuilder(static::$model::getTable());
		}

		/**
		* Provides a connection for all database queries
		*
		* @return mysqli object
		*/
		public function connect() {
			if (self::$connection == null) {
				$config = parse_ini_file("../config.ini");
				self::$connection = new mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
			}
			return self::$connection;
		}

		/**
		* Returns all model instances from the database with eagerly loaded attributes.
		*
		* @param array $options - QueryBuilder configuration
		* @return indexed array
		*/
		public function getAll($options = []) {
			$retVal = [];
			$mysqli = $this -> connect();
			$result = $mysqli -> query($this->builder->select($options));
			while ($row = $result -> fetch_assoc()) {
				$retVal[$row['id']] = new static::$model($row);
			}
			$result -> free();
			// Check if the model has any relationships that need to be eagerly loaded
			if (static::$model::hasRelationships()) {
				$relationships = static::$model::getRelationships();
				// Includes eager loads defined both in model and in options array
				foreach($this->getEagerLoads($options) as $key) {
					$eagerLoad = $relationships[$key];
					if ($eagerLoad['type'] == 'hasMany') {
						// Returns indexed array of arrays and binds them to models
						$eagerCollections = $this -> hasMany($eagerLoad, array_keys($retVal));
						foreach ($eagerCollections as $id => $collection) {
							$retVal[$id]->$key = $collection;
						}
					} else if ($eagerLoad['type'] == 'hasOne') {
						// Returns indexed array of models and binds them to models
						$eagerModels = $this -> hasOne($eagerLoad, array_keys($retVal));
						foreach ($eagerModels as $id => $model) {
							$retVal[$id]->$key = $model;
						}
					}
				}
			}
			return $retVal;
		}

		/**
		* Returns a model instance with a given id and eagerly loaded attributes.
		*
		* @param int id, array options
		* @return Model | null
		*/
		public function get(int $id, array $options = []) {
			$mysqli = $this -> connect();
			$result = $mysqli -> query($this->builder->select()->whereId($id));
			if ($row = $result -> fetch_assoc()) {
				$result -> free();
				$model = new static::$model($row);
				// Check if the model has any relationships that need to be eagerly loaded
				if (static::$model::hasRelationships()) {
					$relationships = static::$model::getRelationships();
					// Includes eager loads defined both in model and in options array
					foreach($this->getEagerLoads($options) as $key) {
						$eagerLoad = $relationships[$key];
						if ($eagerLoad['type'] == 'hasMany') {
							// Returns indexed array with one array and binds it to the model
							$eagerCollections = $this -> hasMany($eagerLoad, [$id]);
							$model -> $key = $eagerCollections[$id];
						} else if ($eagerLoad['type'] == 'hasOne') {
							// Returns indexed array with one model and binds it to the model
							$eagerModels = $this -> hasOne($eagerLoad, [$id]);
							$model -> $key = $eagerModels[$id];
						}
					}
				}
				return $model;
			}
			return null;
		}

		/**
		* Stores a new model instance
		*
		* @param indexed array $attributes
		*/
		public function store(array $attributes) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$query = $this->builder->insert($attributes);
			$stmt -> prepare($query);
			$varTypes = $this -> getVarTypes($attributes);
			$bindingVars = [];
			foreach($attributes as $key => $value)
				$bindingVars[$key] = &$attributes[$key];
			call_user_func_array([$stmt, 'bind_param'], array_merge([$varTypes], $bindingVars));
			$stmt -> execute();
			$stmt -> close();
		}

		/**
		* Updates a model instance in the database
		*
		* @param indexed array $attributes
		*/
		public function update(array $attributes, int $id) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$query = $this->builder->update($attributes)->whereId($id);
			$stmt -> prepare($query);
			$varTypes = $this -> getVarTypes($attributes);
			$bindingVars = [];
			foreach($attributes as $key => $value)
				$bindingVars[$key] = &$attributes[$key];
			call_user_func_array([$stmt, 'bind_param'], array_merge([$varTypes], $bindingVars));
			$stmt -> execute();
			$stmt -> close();
		}

		/**
		* Deletes a model instance from the database.
		*
		* @param int id
		*/
		public function delete(int $id) {
			$mysqli = $this -> connect();
			$mysqli -> query($this->builder->delete()->whereId($id));
		}

		/**
		* Counts all the model instances in a table
		*
		* @return int
		*/
		public function count() {
			$mysqli = $this -> connect();
			$result = $mysqli -> query('select count(*) as count from ' . static::$model::getTable());
			if ($row = $result -> fetch_assoc());
				return $row['count'];
		}

		/**
		* Breaks down var types of attributes to single characters.
		* Used for parameter binding in prepared statements.
		*
		* @param array $attributes
		* @return string
		*/
		private function getVarTypes(array $attributes) {
			$retVal = '';
			foreach($attributes as $attribute) {
				if (is_int($attribute)) {
					$retVal .= 'i';
					continue;
				}
				if (is_float($attribute)) {
					$retVal .= 'd';
					continue;
				}
				$retVal .= 's';
			} return $retVal;
		}

		/**
		* Returns all column names that need to be eagerly loaded.
		* Includes parameters given in the options array.
		*
		* @param array $options
		* @return array
		*/
		private function getEagerLoads(array $options = []) {
			$retVal = static::$model::getEagerLoads();
			if (isset($options['with']))
				if (is_array($options['with']))
					$retVal = array_merge($retVal, $options['with']);
				else $retVal[] = $options['with'];
			return $retVal;
		}

		/**
		* Returns indexed array of arrays with relationship models
		*
		* @param array $el - eager load parameters
		* @param array $keys - integers that are passed as where in parameter
		* @return array
		*/
		private function hasMany(array $el, array $keys) {
			$retVal = [];
			$class = $el['class'];
			$builder = new QueryBuilder($class::getTable());
			$result = $this -> connect() -> query($builder->select(['whereIn' => [$el['column'], $keys]]));
			while ($row = $result -> fetch_assoc()) {
				if (!isset($retVal[$row[$el['column']]]))
					$retVal[$row[$el['column']]] = array();
				$retVal[$row[$el['column']]][] = new $class($row);
			}
			$result -> free();
			return $retVal;
		}

		/**
		* Returns indexed array of relationship models
		*
		* @param array $el - eager load parameters
		* @param array $keys - integers that are passed as where in parameter
		* @return array
		*/
		private function hasOne(array $el, array $keys) {
			$retVal = [];
			$class = $el['class'];
			$builder = new QueryBuilder($class::getTable());
			$result = $this -> connect() -> query($builder->select(['whereIn' => [$el['column'], $keys]]));
			while ($row = $result -> fetch_assoc()) {
				$retVal[$row[$el['column']]] = new $class($row);
			}
			$result -> free();
			return $retVal;
		}

	}

?>