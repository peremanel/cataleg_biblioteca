<?php

	session_start();
	
	// Capsa principal
	echo "<div id=\"principal\">\n";


	// Menú lateral
	echo "<div id=\"barra\">";
	echo "<div id=\"logo\"><img src=\"logo.png\"></div>";
	echo "<div id=\"titol_barra\"><h2>Biblioteca</h2>";
	mostra_grandaria_cataleg();
	echo "</div>";
	echo "<div id=\"menu\"><hr>";
	echo "<form action=\"index.php\"><input type=\"submit\" value=\"cercar\"></form>\n<br>";
	if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
		echo "<form action=\"fitxa_alta.php\"><input type=\"submit\" value=\"nova fitxa\"></form>\n<br>\n";
		echo "<form action=\"prestecs_vius.php\"><input type=\"submit\" value=\"préstecs\"></form>\n";
	}
	echo "</div></div>";


	// Títol aplicació
	echo "<div id=\"cos\">\n";
	echo "<div id=\"titol_cos\">\n";
	echo "<h1>Catàleg de la biblioteca</h1>\n";
	echo "</div>\n<div id=\"contingut_cos\">";


	function mostra_grandaria_cataleg() {
		//Mostra el nombre de títols i d'exemplars
		// a la base de dades
		include 'app_config.php';
		$connexio = mysqli_connect($host,$user,$password,$dbase);
		if (mysqli_connect_errno())	{
  			echo "<p class=\"error\">Error en comptar</p>\n";
  		} else {
  			// fitxes
  			$textconsulta = "SELECT COUNT(*) FROM fitxa;";
			if ($result = mysqli_query($connexio, $textconsulta)) {
				$fila =mysqli_fetch_row($result);
				$titols = $fila[0];
			}
			// exemplars
  			$textconsulta = "SELECT COUNT(*) FROM exemplar WHERE baixa IS NULL;";
			if ($result = mysqli_query($connexio, $textconsulta)) {
				$fila =mysqli_fetch_row($result);
				$exemplars = $fila[0];
			}
			echo "<ul><li>".$titols." fitxes</li>\n";
			echo "<li>".$exemplars." exemplars</li></ul>\n";
			mysqli_free_result($result);
  		}
	}

?>
