<!doctype html>
<html lang="sr">

	<head>
		<link charset="UTF-8">
		<link rel="icon" href="http://www.zemljotres.net/images/favicon.ico?" />
	    <link rel="shortcut icon" type="favicon/ico" href="images/favicon.ico">
	    <meta name="keywords" content="<?php if(defined('KEYWORDS')) {print KEYWORDS;} ?>">
	    <meta name="description" content="<?php if(defined('DESCRIPTION')) {print DESCRIPTION;} ?>">
		<meta name="author" content="Veselin Grcić">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="jscript/jquery-1.11.0.js"></script>
		<script src="jscript/script.js"></script>
		<meta charset="utf-8">
		<title><?php if(defined('TITLE')) {print TITLE;} else {print 'Zemljotres';}	?></title>
	</head>

	<body>


		<!-- Big ass mirror image -->
		<div class="mirror"></div>



		<!-- Header begins -->

		<header>
			<div class="container">
				<img src="images/header.gif" height="100px" />
			</div>
		</header>

		<!-- Header ends -->



		<!-- Navigation begins -->

		<nav>
			<div class="btn-holder">
				<ul>
					<li><a href="index.php">Naslovna</a></li>
					<li><a href="audio.php">Audio</a></li>
					<li><a href="galerija.php">Galerija</a></li>
					<li><a href="bio.php">Biografija</a></li>
					<li><a href="kontakt.php">Kontakt</a></li>
				</ul>
			</div>
		</nav>

		<!-- Navigation ends -->



		<!-- Main content begins -->

		<div class="wrapper">
			<main>