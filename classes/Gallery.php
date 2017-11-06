<?php 

	class Gallery extends Model {

		protected static $table = 'galleries';

		protected static $fillable = ['id', 'name'];

		protected static $eagerLoads = ['images'];

		protected static $relationships = [
			'images' => [
				'type' => 'hasMany',
				'class' => 'Image',
				'column' => 'gallery_id'],
		];

		

	}

?>