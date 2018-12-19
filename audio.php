<?php
	
	// autoloader
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.php';
	});

	define ('KEYWORDS', 'Zemljotres, pesme, diskografija, audio, muzika');
	define ('DESCRIPTION', 'Diskografija benda');
	
	if (isset($_GET['album']) && !is_nan($_GET['album'])) {

		$albumsRepository = new AlbumsRepository;
		$album = $albumsRepository -> get($_GET['album']);
		
		if ($album == null) {
			// If album parameter is given, but no album is found, redirect to audio page
			header('Location: audio.php');
		}
		


		// ALBUM PAGE

		define ('TITLE', $album -> name);
		require_once 'templates/header.php'; ?>

		<h1>Audio</h1>

		<section class="album-info">

			<div class="cover">
				<img src="images/<?php print $album -> photo ?>">
			</div>
		
			<div class="info">
				<h2><?php print $album -> name ?><br/><small class="dim"><?php print $album -> year ?></small></h2>
				<?php
					if (isset($album -> description))
						print "<p>$album->description</p>";
					if (isset($album -> info))
						print "<p>$album->info</p>"
				 ?>
			</div>

		</section>

		<section class="album-tracks">

			<div class="player">
				<audio onended="refreshButtons()">
					<source id="source" src="tracks/tako-sam-mlad.mp3" type="audio/mpeg">
				</audio>
				<?php 
					foreach ($album -> tracks as $track) { ?>
						<div class="track" id="<?php print $track -> id ?>">
							<p><?php print $track -> sequence . " - " . $track -> name ?></p>
							<img class="play-btn" height="17px" width="17px" src="images/play_button.png"
								 onclick="playTrack(<?php print $track -> id ?>, '<?php print $track -> audio ?>')">
							<img height="17px" width="17px" src="images/text_button.png" onclick="showLyrics(<?php print $track -> id ?>)">
							<?php if (isset($track -> video)) {
								print "<a target=\"_blank\" href=\"$track->video\"><img height=\"17px\" width=\"17px\" src=\"images/screen_button.png\"></a>";
							} ?>
						</div>
				<?php } ?>
			</div>

			<div class="lyrics">
				<?php
					foreach ($album -> tracks as $track) { ?>
						<div id="lyrics-<?php print $track -> id ?>">
							<h3><?php print $track -> name ?></h3>
							<p><?php print $track -> lyrics ?></p>
						</div>
					<?php }
				?>

			</div>

		</section>

		<?php

	} else {



		// AUDIO PAGE

		define ('TITLE', 'Audio');
		require_once 'templates/header.php'; ?>

    	<h1>Audio</h1>

    	<section class="albums">

    	<?php

    		$albumsRepository = new AlbumsRepository;
    		$albums = $albumsRepository -> getAll();

    		foreach ($albums as $album) { ?>

    			<div class="album">
    				<h3><a href="audio.php?album=<?php print $album -> id ?>"><?php print $album -> name; ?></a>
    					<span class="dim">(<?php print $album -> year ?>)</span></h3>
    				<div class="cover">
    					<img src="images/<?php print $album -> photo ?>">
    				</div>
    				<div class="tracks">
	    				<ol>
		   					<?php 
		    					foreach ($album -> tracks as $track) {
		   							print "<li>".$track -> name."</li>";
		   						}
	    					?>
	    				</ol>
    				</div>
    			</div>
    		<?php } ?>

    	</section>
	
<?php
	}
	require_once 'templates/footer.php';
?>