<?php

	/**
	* QueryBuilder serves as a sql query generator
	* It does not execute any of the generated sql code
	*
	* @author Veselin Grcic
	*/
	class QueryBuilder {

		private $table;
		private $query = '';

		public function __construct($table) {
			$this -> table = $table;
		}

		/**
		* Builds a select query.
		* If no column options are given, all columns will be selected.
		*
		* @param array $options
		* @return QueryBuilder
		*/
		public function select(array $options = []) {
			$select = array_key_exists('columns', $options) ? implode(',', $options['columns']) : '*';
			$this -> query = 'select ' . $select . ' from ' . $this -> table;
			unset($options['columns']);
			$this -> handleOptions($options);
			return $this;
		}

		/**
		* Builds a select query for a single row retrieval.
		*
		* @param int $id
		* @param array $options
		* @return QueryBuilder
		*/
		public function get(int $id, array $options = []) {
			$this -> getAll();
			$this -> query .= ' where id = ' . $id;
		}

		/**
		* Builds a query for updating records.
		* If no filtering condition is given, all records will be updated.
		*
		* @param array $vars
		* @param array $options
		* @return QueryBuilder
		*/
		public function update(array $vars, array $options = []) {
			$query = 'update ' . $this->table . ' set ';
			foreach($vars as $key => $value) {
				$query .= $key . ' = ?, ';
			}
			$this -> query = trim($query, ', ');
			$this -> handleOptions($options);
			return $this;
		}

		/**
		* Builds a query for a records insertion.
		*
		* @param array $vars
		* @return QueryBuilder
		*/
		public function insert(array $vars) {
			$query = 'insert into ' . $this->table . ' (' . implode(', ', array_keys($vars)) . ') values (';
			foreach ($vars as $var) {
				$query .= '?, ';
			}
			$this -> query = trim($query, ', ') . ')';
			return $this;
		}

		/**
		* Builds a query for deleting records.
		* If no filtering condition is given, all records will be deleted.
		*
		* @param array $options
		* @return QueryBuilder
		*/
		public function delete(array $options = []) {
			$this -> query = 'delete from ' . $this->table;
			$this -> handleOptions($options);
			return $this;
		}

		/**
		* Appends the query with order by clause.
		* Options may have one or two arguments. If only one is given, rowss will be sorted in a descending order
		*
		* @param array $options
		* @return QueryBuilder
		*/
		public function orderBy(array $options) {
			$this -> query .= ' order by ' . $options[0] . ' ' . ($options[1] ? $options[1] : 'desc');
			return $this;
		}

		/**
		* Appends the query with where clause.
		* Options may have two or three arguments. If only two are given, the operator '=' will be used.
		*
		* @param array $options
		* @return QueryBuilder
		*/
		public function where(array $options) {
			$this->query .= ' where ' . $options[0] . ' ';
			if (count($options) == 2)
				$this->query .= '= ' . $options[1];
			else $this -> query .= $options[1] . ' ' . $options[2];
			return $this;
		}

		/**
		* Appends the query for selection by id
		*
		* @param int $id
		* @return QueryBuilder
		*/
		public function whereId($id) {
			$this->query .= ' where id = ' . $id;
			return $this;
		}

		public function whereIn(array $values) {
			$this->query .= ' where ' . $values[0] . ' in (' . implode(', ', $values[1]) . ')';
			return $this;
		}

		/**
		* Handles the options provided to all query building functions
		* Options may be: orderBy, where, whereId
		*
		* @param array $options
		*/
		public function handleOptions(array $options) {
			foreach ($options as $key => $value) {
				$this -> {$key}($value);
			}
		}

		public function __toString() {
			return $this -> query;
		}

	}

?>