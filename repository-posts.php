<?php

	require_once 'model-post.php';
	require_once 'repository.php';

	class PostsRepository extends Repository {

		public function getAll() {
			$posts = [];
			$mysqli = $this -> connect();
			$query = "select id, heading, content, sequence from posts order by sequence desc";
			$result = $mysqli -> query($query);
			while ($row = $result -> fetch_assoc()) {
				$posts[] = new Post($row);
			}
			$result -> free();
			return $posts;
		}

		public function get(int $id) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("select heading, content, sequence from posts where id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($heading, $content, $sequence);
			if ($stmt -> fetch()) {
				$stmt -> close();
				return new Post([
					'id' => $id,
					'heading' => $heading,
					'content' => $content,
					'sequence' => $sequence,
				]);
			} return null;
		}

		public function store(Post $post) {
			$mysqli = $this -> connect();
			$query = "insert into posts(heading, content, sequence) values(?, ?, ?)";
			$count = $this->count();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare($query);
			$stmt -> bind_param("ssi", $post->heading, $post->content, $count);
			$stmt -> execute();
			$stmt -> close();
		}

		public function update(Post $post) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("update posts set heading = ?, content = ? where id = ?");
			$stmt -> bind_param("ssi", $post -> heading, $post -> content, $post -> id);
			$stmt -> execute();
			$stmt -> close();
		}

		public function delete(int $id) {
			$post = $this -> get($id);
			if ($post != null) {
				$mysqli = $this -> connect();
				$stmt = $mysqli -> stmt_init();
				$mysqli -> begin_transaction();
				// delete the target post and
				$stmt -> prepare("delete from posts where id = ?");
				$stmt -> bind_param("i", $id);
				$stmt -> execute();
				// push all succeeding posts up
				$sequence = $post -> sequence;
				$stmt -> prepare("update posts set sequence = sequence - 1 where sequence > ?");
				$stmt -> bind_param("i", $sequence);
				$stmt -> execute();
				$mysqli -> commit();
				$stmt -> close();
				return true;
			} return false;
			
		}

		public function count() : int {
			$mysqli = $this -> connect();
			$result = $mysqli -> query("select count(*) as count from posts");
			$row = $result -> fetch_assoc();
			$result -> free();
			return $row['count'];
		}

		public function sequenceDown(int $id)	{
			$post = $this -> get($id);
			if ($post != null && $post -> sequence > 0) { // post exists and is not the last
				$sequence = $post -> sequence;				
				$newSequence = $sequence - 1;
				$mysqli = $this -> connect();
				$stmt = $mysqli -> stmt_init();
				$mysqli -> begin_transaction();
				// move the target post out of the way -> sequence = -1
				$stmt -> prepare("update posts set sequence = -1 where sequence = ?");
				$stmt -> bind_param("i", $sequence);
				$stmt -> execute();
				// give the preceding post the sequence of target post
				$stmt -> prepare("update posts set sequence = ? where sequence = ?");
				$stmt -> bind_param("ii", $sequence, $newSequence);
				$stmt -> execute();
				// give the target post the sequence that is one less than original
				$stmt -> prepare("update posts set sequence = ? where id = ?");
				$stmt -> bind_param("ii", $newSequence, $id);
				$stmt -> execute();

				$mysqli -> commit();
				$stmt -> close();
				return true;
			} return false;
		}

		public function sequenceUp(int $id) {
			$count = $this -> count();
			$post = $this -> get($id);
			if ($post != null && $post -> sequence != $count - 1) { // post exists and is not the first
				$sequence = $post -> sequence;
				$newSequence = $sequence + 1;
				$mysqli = $this -> connect();
				$stmt = $mysqli -> stmt_init();
				$mysqli -> begin_transaction();
				// move the target post out of the way -> sequence = -1
				$stmt -> prepare("update posts set sequence = -1 where sequence = ?");
				$stmt -> bind_param("i", $sequence);
				$stmt -> execute();
				// give the succeeding post the sequence of target post
				$stmt -> prepare("update posts set sequence = ? where sequence = ?");
				$stmt -> bind_param("ii", $sequence, $newSequence);
				$stmt -> execute();
				// give the target post the sequence that is one larger than original
				$stmt -> prepare("update posts set sequence = ? where id = ?");
				$stmt -> bind_param("ii", $newSequence, $id);
				$stmt -> execute();

				$mysqli -> commit();
				$stmt -> close();
				return true;
			} return false;
		}

	}

?>