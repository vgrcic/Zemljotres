<?php

	require_once 'model-track.php';
	require_once 'repository.php';

	class TracksRepository extends Repository {

		public function getAllForAlbum($album) {
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("select id, name, audio, sequence, lyrics, video from tracks where album_id = ? order by sequence asc");
			$stmt -> bind_param("i", $album);
			$stmt -> execute();
			$stmt -> bind_result($id, $name, $audio, $sequence, $lyrics, $video);
			$tracks = array();
			while ($stmt -> fetch()) {
				$track = new Track;
				$track -> id = $id;
				$track -> name = $name;
				$track -> sequence = $sequence;
				$track -> audio = $audio;
				$track -> lyrics = $lyrics;
				$track -> video = $video;
				$tracks[] = $track;
			}
			return $tracks;
		}

		public function getAll() {
			$mysqli = $this -> connect();
			$result = $mysqli -> query("select id, name, audio, sequence, album_id, lyrics, video from tracks");
			$tracks = array();
			while ($row = $result -> fetch_assoc()) {
				$track = new Track;
				$track -> id = $row['id'];
				$track -> name = $row['name'];
				$track -> audio = $row['audio'];
				$track -> sequence = $row['sequence'];
				$track -> album_id = $row['album_id'];
				$track -> lyrics = $row['lyrics'];
				$track -> video = $row['video'];
				$tracks[] = $track;
			}
			return $tracks;
		}

	}

?>