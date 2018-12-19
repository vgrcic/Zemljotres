<?php

	/**
	* Repository executes queries created by the QueryBuilder class.
	*
	* @author Veselin Grcic
	*/
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
			// Includes eager loads defined both in model and in options array
			if (static::$model::hasRelationships()) {
				$relationships = static::$model::getRelationships();				
				foreach($this->getEagerLoads($options) as $key) {
					$eagerLoad = $relationships[$key];
					$this -> manageEagerLoads($eagerLoad, $retVal);
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
				// Includes eager loads defined both in model and in options array
				if (static::$model::hasRelationships()) {
					$relationships = static::$model::getRelationships();				
					foreach($this->getEagerLoads($options) as $key) {
						$eagerLoad = $relationships[$key];
						$this -> manageEagerLoads($eagerLoad, $model);
					}
				}
				return $model;
			} return null;
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
			if ($row = $result -> fetch_assoc())
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
		* Populates models in indexed array with relationship models
		*
		* @param array $el - eager load parameters
		* @param indexed array | model $models - by reference
		*/
		private function manageEagerLoads(array $eagerLoad, &$models) {
			// if object is given, transform it into indexed array
			if (!is_array($models)) {
				$transformed = $models->id;
				$models = [$models->id => $models];
			}

			$class = $eagerLoad['class'];
			$column = $eagerLoad['column'];
			$table = $class::getTable();
			$builder = new QueryBuilder($class::getTable());
			$result = $this -> connect() -> query($builder->select(['whereIn' => [$column, array_keys($models)]]));

			if ($eagerLoad['type'] == 'hasMany') {
				while ($row = $result -> fetch_assoc()) {
					$models[$row[$column]] -> insertIntoRelationshipArray($table, new $class($row));
				}
			} elseif ($eagerLoad['type'] == 'hasOne') {
				while ($row = $result -> fetch_assoc()) {
					$models[$row[$column]] -> $table = new $class($row);
				}
			}
			
			$result -> free();

			// if object was given and transformed, extract original value
			if (isset($transformed)) {
				$models = $models[$transformed];
			}

		}

	}

?>