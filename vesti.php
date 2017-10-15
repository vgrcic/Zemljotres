<?php

	include ('templates/sql_connection.php');
	if (!isset($_GET['v'])) {
		$header = 'Vesti';
		$result = mysql_query("SELECT heading, content, sequence FROM news ORDER BY sequence ASC");
	}

?>