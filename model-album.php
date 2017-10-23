<?php

	require_once 'model.php';

	class Album extends Model {

		protected $fillable = [
			'id', 'name', 'year', 'photo', 'tracks', 'description', 'info',
		];

	}

?>