<?php

	class Track extends Model {

		protected static $fillable = ['id', 'name', 'audio', 'sequence', 'album_id', 'lyrics', 'video'];

		protected static $table = 'tracks';

	}

?>