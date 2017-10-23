<?php

	require_once 'model.php';

	class Member extends Model {
		
		protected $fillable = ['id', 'first', 'last', 'instrument', 'photo', 'bio', 'active'];
		
	}

?>