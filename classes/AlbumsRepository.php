<?php

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
				$album = new Album($row);
				$album -> tracks = $this->tracksRepository->getAllForAlbum($album->id);
				$albums[] = $album;
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
				$album = new Album([
					'id' => $id,
					'name' => $name,
					'year' => $year,
					'photo' => $photo,
					'description' => $description,
					'info' => $info,
					'tracks' => $this->tracksRepository->getAllForAlbum($id),
				]);
				return $album;
			} return null;
		}

	}

?>