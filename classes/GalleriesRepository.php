<?php

	class GalleriesRepository extends Repository {

		/**
		* Fetches galleries with only names and image counts
		*
		* @return array
		*/
		public function getIndex() {
			$galleries = [];
			$mysqli = $this -> connect();
			$result = $mysqli -> query("select gallery_id as id, count(gallery_id) as count, g.name from images
										left join galleries as g on images.gallery_id = g.id group by gallery_id");
			while ($row = $result -> fetch_assoc()) {
				$galleries[] = new Gallery($row);
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
			$gallery = null;
			$mysqli = $this -> connect();
			$stmt = $mysqli -> stmt_init();
			$stmt -> prepare("select g.name, count(gallery_id) as count from images
							left join galleries as g on images.gallery_id = g.id where g.id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($name, $count);
			if ($stmt -> fetch()) {
				$gallery = new Gallery([
					'name' => $name,
					'count' => $count,
				]);
			} else return null;
			// Get the images for the gallery
			$stmt -> prepare("select id from images where gallery_id = ?");
			$stmt -> bind_param("i", $id);
			$stmt -> execute();
			$stmt -> bind_result($image);
			$images = [];
			while ($stmt -> fetch()) {
				$images[] = $image;
			}
			$gallery -> images = $images;
			return $gallery;
		}

	}

?>