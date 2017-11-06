<?php

	class Member extends Model {
		
		protected static $fillable = ['id', 'first', 'last', 'instrument', 'photo', 'bio', 'active'];
		
		protected static $table = 'members';

	}

?>