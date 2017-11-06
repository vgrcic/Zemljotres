<?php

	class Post extends Model {
		
		protected static $fillable = ['id', 'heading', 'content', 'sequence'];

		protected static $table = 'posts';

	}

?>