<?php

	// autoloader
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.php';
	});

	define ('TITLE', 'Zemljotres');
	define ('KEYWORDS', 'Zemljotres, bend, blues, rock, Zrenjanin, Aradac');
	define ('DESCRIPTION', 'Web prezentacija benda Zemljotres');

	require_once 'templates/header.php';
?>
	
	<!-- Slideshow script -->
	<script>

		$(document).ready(function() {
			$(".slideshow img[id!=1]").hide();
			setInterval(slide, 5000);
		});

		var img = 1;
		var imgMax = 10;

		function slide() {
			$("#" + img++).fadeTo(200, 0);
			if (img > imgMax)
				img = 1;
			$("#" + img).fadeTo(200,1);
		};

	</script>

	<section class="slideshow">
	    <div class="frame">	    	
			<!--<img id="repeat" height="500px" src="images/gallery/plakat.jpg">-->
			<img id="10" height="500px" src="images/gallery/30.jpg">
			<img id="9" height="500px" src="images/gallery/29.jpg">
			<img id="8" height="500px" src="images/gallery/28.jpg">
			<img id="7" height="500px" src="images/gallery/27.jpg">
			<img id="6" height="500px" src="images/gallery/26.jpg">
			<img id="5" height="500px" src="images/gallery/25.jpg">
			<img id="4" height="500px" src="images/gallery/24.jpg">
			<img id="3" height="500px" src="images/gallery/23.jpg">
			<img id="2" height="500px" src="images/gallery/22.jpg">
			<img id="1" height="500px" src="images/gallery/1.jpg" />
		</div>
	</section>

	<h1>Vesti</h1>

	<section class="posts">

		<?php
			// Get posts and iterate
			$postsRepository = new PostsRepository;
			$posts = $postsRepository -> getAll();
			foreach ($posts as $post) { ?>

			<div class="post">
				<h5><?php print $post -> heading ?></h5>
				<p><?php print $post -> content ?></p>
			</div>

		<?php } ?>

	</section>

<?php
	require_once 'templates/footer.php';
?>