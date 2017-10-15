<?php

	require_once 'model-album.php';
	require_once 'repository.php';
	require_once 'repository-tracks.php';

	class AlbumsRepository extends Repository {

		private $tracksRepository;

		public function __construct() {
			$this -> tracksRepository = new TracksRepository;
		}

		public function getAll() {
			$albums = array();
			$mysqli = $this -> connect();
			$result = $mysqli -> query("select id, name, year, photo, description, info from albums order by year desc");
			while ($row = $result -> fetch_assoc()) {
				$album = new Album;
				$album -> id = $row['id'];
				$album -> name = $row['name'];
				$album -> year = $row['year'];
				$album -> photo = $row['photo'];
				$album -> description = $row['description'];
				$album -> info = $row['info'];
				$albums[] = $album;
			}
			// Assign tracks to albums
			$tracks = $this -> tracksRepository -> getAll();
			foreach($albums as $album) {
				foreach($tracks as $id => $track) {
					if ($track -> album_id === $album -> id) {
						$album -> tracks[] = $track;
						unset($tracks[$id]);
					}
				}
			}
			return $albums;
		}

		public function get($id) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("select name, year, photo, description, info from albums where id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($name, $year, $photo, $description, $info);
			if ($stmt -> fetch()) {
				$stmt -> close();
				$album = new Album;
				$album -> id = $id;
				$album -> name = $name;
				$album -> year = $year;
				$album -> photo = $photo;
				$album -> description = $description;
				$album -> info = $info;
				$album -> tracks = $this -> tracksRepository -> getAllForAlbum($id);
				return $album;
			} return null;
		}

	}

?>