<?php
	define ('TITLE', 'Kontakt');
	define ('KEYWORDS', 'Zemljotres, kontakt, e-mail');
	define ('DESCRIPTION', 'Kontakt informacije');
	include ('templates/header.php');
?>
    	<h1>Kontakt</h1>

    	<section class="contact-info">

	    	<h3>Informacije</h3>

	    	<table>
	    		<tr><th>Ime:</th>
	    			<td>Janko Hrubik</td></tr>
	    		<tr><th>Adresa:</th>
	    			<td>Šturova 11, 23207 Aradac</td></tr>
	    		<tr><th>Telefon:</th>
	    			<td>+381 64 57 55 407</td></tr>
	    		<tr><th>E-mail:</th>
	    			<td>zemljotresbend@gmail.com</td></tr>
	    	</table>

    	</section>

    	<section class="contact-email">

    		<h3>E-mail forma</h3>

<?php

		if ($_SERVER['REQUEST_METHOD']=='POST') {

			if(empty($_POST['ime']) ||
				empty($_POST['telefon']) ||
				empty($_POST['tema']) ||
				empty($_POST['poruka'])) {

				print "<p>E-mail forma nije pravilno popunjena</p>";

			} else {
				$poruka = $_POST['poruka'] . "\r\n" . "\r\n" . $_POST['ime'] . "\r\n" . $_POST['telefon'];
				$poruka = wordwrap($poruka, 70, "\r\n");
				if (mail('vgrcic@hotmail.com', $_POST['tema'], $poruka, "From:email@zemljotres.net", $_POST['ime'])) {
					print "<p style='color:darkgreen'>Vaša poruka je uspešno poslata!</p>";
				} else {
					print "<p style='color:red'>Vaša poruka nije uspešno poslata.</p>";
				}
			}

		} else {

?>

			<form id="email" action="kontakt.php" method="POST" onsubmit="return validateEmailForm()">
				<table>
					<tr><th>Ime:</th>
						<td><input type="text" name="ime" id="ime"><span class="error"></span></td>
					</tr>
					<tr><th>Telefon:</th>
						<td><input type="text" name="telefon" id="telefon"><span class="error"></span></td>
					</tr>
					<tr><th>Tema:</th>
						<td><input type="text" name="tema" id="tema"><span class="error"></span></td>
					</tr>
					<tr><th>Poruka:</th>
						<td><textarea name="poruka" id="poruka"></textarea><span class="error"></span></td>
					</tr>
					<tr><th></th>
						<td><input type="submit" value="Pošalji"></td>
					</tr>
				</table>
			</form>

		</section>

		<?php }
	
	include ('templates/footer.php');

?>