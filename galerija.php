<?php

	// autoloader
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.php';
	});

	define ('TITLE', 'Galerija');
	define ('KEYWORDS', 'Zemljotres, slike, galerija');
	define ('DESCRIPTION', 'Slike benda');
	require_once 'templates/header.php';

	$galleriesRepository = new GalleriesRepository;

	if (isset($_GET['gallery']) && !is_nan($_GET['gallery'])) {
		$gallery = $galleriesRepository -> get($_GET['gallery']);
		if ($gallery == null) {
			header('Location: galerija.php');
		}
	}

?>



    <h1>Galerija</h1>


    
    <section class="gallery-select">
    	
    	<form action="galerija.php" method="GET">
			<select name="gallery">
				<option>Odaberi galeriju:</option>
				<?php
					foreach ($galleriesRepository -> getIndex() as $gal) { ?>
					<option value="<?php print $gal -> id ?>">
						<?php print $gal -> name ?> (<?php print $gal -> count ?> slika)</option>
				<?php } ?>
			</select>
			<input type="submit" value="Prikaži">
		</form>

    </section>
	


    <section class="gallery-screen">
    	
    	<div id="screen">
			<img id="picture" src="" alt="active photo">
		</div>

    </section>


	
	
<?php
	
	if (isset($gallery)) { ?>

	<section class="gallery-slider">

		<h3><?php print $gallery -> name ?></h3>
		<div class="slider-holder">
			<div class="slider" style="width: <?php print $gallery->count * 86 ?>px">

	<?php
		foreach ($gallery -> images as $image) { ?>
			<img onClick="gallery(<?php print $image ?>)"
				 src="images/gallery/thumb/<?php print $image ?>.jpg"
				 alt="thumbnail" tabindex="0" />
	<?php } ?>
	
			</div>
		</div>

	</section>

<?php
	}
	require_once 'templates/footer.php';
?>