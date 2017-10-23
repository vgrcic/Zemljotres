<?php

	require_once 'model-member.php';
	require_once 'repository.php';

	class MembersRepository extends Repository {

		public function getAll() {
			$members = array();
			$mysqli = $this -> connect();
			$query = "select id, first, last, bio, instrument, photo from members where active = 1 order by id asc";
			$result = $mysqli -> query($query);
			while ($row = $result -> fetch_assoc()) {
				$members[] = new Member($row);
			}
			$result -> free();
			return $members;
		}

	}

?>