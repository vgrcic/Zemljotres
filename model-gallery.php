<?php 

	require_once 'model.php';

	class Gallery extends Model {

		protected $fillable = ['id', 'name', 'images', 'count'];

	}

?>