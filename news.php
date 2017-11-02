<?php

	// autoloader
	spl_autoload_register(function($class) {
		require_once 'classes/' . $class . '.php';
	});

	session_start();
	$config = parse_ini_file("../config.ini");
	if(isset($_POST['password'])) {
		if ($_POST['password'] == $config['cpanelpass'])
			$_SESSION['login'] = true;
	}

?>

<!doctype html>
<html>
	<head>
		<title>News control panel</title>
		<link charset="UTF-8">
		<meta charset="utf-8">
		<meta name="author" content="Veselin Grcić">
		<link rel="stylesheet" type="text/css" href="css/style-news.css">
		<script src="jscript/jquery-1.11.0.js"></script>
		<script src="jscript/script-news.js"></script>
	</head>
	<body>
		
		<!-- Header begins -->

		<header>
			<div class="container">
				<h1>Control panel</h1>
				<h3>News section</h3>
			</div>
		</header>

		<!-- Header ends -->



		<!-- Navigation begins -->

		<nav>
			<div class="container">
				<div class="left">
				<ul>
					<li><a href="news.php">News</a></li>
					<li><a href="#">Images</a></li>
				</ul>
				</div>
				<div class="right">
					<li><a href="http://www.zemljotres.net">Zemljotres</a></li>
				</div>
			</div>
		</nav>

		<!-- Navigation ends -->

		<main>

		<?php

			if (!isset($_SESSION['login'])) {
			// Login form if session is not active

		?>

		
	

		<!-- Login form begins -->

		<section class="login">
			<div class="container">
				<form action="news.php" method="POST">
					<input type="password" class="password" name="password" autofocus autocomplete="off" >
					<input type="submit" class="submit" name="submit" value="Login" default>
				</form>
			</div>
		</section>

		<!-- Login form ends -->
			
		<?php

			} else {
			// Control panel if session is active

				$postsRepository = new PostsRepository;

				// ACTIONS

				if (isset($_POST['heading'], $_POST['content'], $_POST['target'])) {
					$post = new Post;
					$post -> heading = $_POST['heading'];
					$post -> content = preg_replace('/(\r\n|\r|\n)/', '<br/>', $_POST['content']);
					if (is_numeric($_POST['target'])) {
						$post -> id = $_POST['target'];
						$postsRepository -> update($post);
					} else {
						$postsRepository -> store($post);
					}
						
				}
				
				elseif (isset($_GET['delete'])) {
					$postsRepository -> delete($_GET['delete']);
				}
				
				elseif (isset($_GET['up'])) {
					$postsRepository -> sequenceUp($_GET['up']);
				}
				
				elseif (isset($_GET['down'])) {
					$postsRepository -> sequenceDown($_GET['down']);
				}


				// Fetch posts and iterate below:
				$posts = $postsRepository -> getAll();
				$count = $postsRepository -> count();

		?>

			<!-- Post creation form begins -->

			<section class="post-create">
				<div class="container">
					<h2>Create or edit posts</h2>
					<form action="news.php" method="POST" onsubmit="return validatePostForm()">
						<table>
							
							<tr><th>Option:</th>
								<td><select class="target" id="target" name="target" onchange="optionChange()">
										<option class="option" value="new">New post</option>
									<?php // Iterating edit options
										foreach($posts as $post) { ?>
											<option class="option" value=<?php print "\"$post->id\"" ?>>Edit: <?php print $post->heading ?></option>";
									<?php } ?>
									</select></td>

							<tr>
								<th>Heading:</th>
								<td>
									<input class="heading" id="heading" type="text" name="heading">
									<span class="error"></span> <!-- Error message injected with JQuery -->
								</td>
							</tr>

							<tr>
								<th>Content:</th>
								<td>
									<textarea class="content" id="content" name="content"></textarea>
									<span class="error"></span> <!-- Error message injected with JQuery -->
								</td>
							</tr>

							<tr><th></th>
								<td><input class="submit" id="submit" type="submit" value="Post"></td></tr>

						</table>
					</form>
					<!-- <button onClick="addHyperlink()">ubaci link</button> -->
				</div>
			</section>

			<!-- Post creation form ends -->



			<!-- Post review section begins -->

			<section class="post-view">
				<div class="container">

					<h2>All posts</h2>

					<table id="posts">
						<tr><th>Heading</th><th>Content</th><th>Actions</th></tr>

						<?php

							foreach ($posts as $post) { // Print posts ?>

								<tr id=<?php print "\"post-$post->id\"" ?>>
									<td class="heading"><?php print $post->heading ?></td>
									<td class="content"><?php print $post->content ?></td>
									<td class="action">
										<div class="action-buttons">
											<a <?php if ($post -> sequence == $count - 1) { ?> class="hidden" <?php } ?>
												href=<?php print "\"news.php?up=$post->id\"" ?>>up</a>
											<a <?php if ($post -> sequence == 0) { ?> class="hidden" <?php } ?>
												href=<?php print "\"news.php?down=$post->id\"" ?>>down</a>
											<a href=<?php print "\"news.php?delete=$post->id\"" ?>>delete</a></div></td>
										
								</tr>

						<?php } ?>

					</table>

				</div>
			</section>
			
			<!-- Post review section ends -->



		<?php } // If logged in ends ?>

		<div class="pusher"></div> <!-- Stretches the MAIN DIV to make room for the FOOTER -->

		</main>

		<!-- Footer begins -->

		<footer>
			<div class="container">
				<p>Author: Veselin Grcić</p>
			</div>					
		</footer>

		<!-- Footer ends -->

	</body>
</html>