<?php

	class Album extends Model {

		protected static $table = 'albums';

		protected static $fillable = ['id', 'name', 'year', 'photo', 'description', 'info'];

		protected static $eagerLoads = ['tracks'];

		protected static $relationships = [
			'tracks' => [
				'type' => 'hasMany',
				'class' => 'Track',
				'column' => 'album_id'],
		];

	}

?>