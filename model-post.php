<?php

	require_once 'model.php';

	class Post extends Model {
		
		protected $fillable = ['id', 'heading', 'content', 'sequence'];

	}

?>