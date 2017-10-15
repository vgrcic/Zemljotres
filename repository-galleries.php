<?php

	require_once 'model-gallery.php';
	require_once 'repository.php';

	class GalleriesRepository extends Repository {

		/**
		* Fetches galleries with only names and image counts
		*
		* @return array
		*/
		public function getIndex() {
			$galleries = array();
			$mysqli = $this -> connect();
			$result = $mysqli -> query("select id, name from galleries");
			while ($row = $result -> fetch_assoc()) {
				$galleries[$row['id']] = new Gallery;
				$galleries[$row['id']] -> id = $row['id'];
				$galleries[$row['id']] -> name = $row['name'];
			}
			$result = $mysqli -> query("select gallery_id, count(gallery_id) as count from images group by gallery_id");
			while ($row = $result -> fetch_assoc()) {
				$galleries[$row['gallery_id']] -> count = $row['count'];
			}
			return $galleries;
		}

		/**
		* Fetches a Gallery of images with a given id
		* 
		* @param	int $id
		* @return	Gallery or null
		*/		
		public function get($id) {
			$gallery = new Gallery;
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("select name from galleries where id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($name);
			if ($stmt -> fetch()) {
				$gallery -> name = $name;
			} else return null;
			// Get the images for the gallery
			$stmt -> prepare("select id from images where gallery_id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($image);
			while ($stmt -> fetch()) {
				$gallery -> images[] = $image;
			}
			// Set count attribute
			$gallery -> count = count($gallery -> images);
			return $gallery;
		}

	}

?>