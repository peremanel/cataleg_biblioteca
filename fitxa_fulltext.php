<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Mostra el resultat de la cerca de fitxes
	per a cerques a text complet
	Catàleg de biblioteca
	Fundació Pilar i Joan Miró a Mallorca
	Versió 0.2
	Data: 22/9/2020
	Incorpora camp notes a l'índex
-->

<?php
	// Control d'accés dels usuaris
	include "app_config.php";
	include "app_acces.php";

	session_start();
	if ($_SESSION['bib_nivell'] > $privilegi_consulta) {
		header("Location:".$pagina_rebuig);
	}
?>

<?php
	// Càrrega de constants
	include 'app_config.php';

	// CAPÇALERA
	include "app_capsalera.php";

	// COS PRINCIPAL
	
	// Càrrega de funcions
	include 'app_funcions.php';

	$connexio = mysqli_connect($host,$user,$password,$dbase);

	if (mysqli_connect_errno())	{
  		echo "<p class=\"error\">Error 01: No ha pogut connectar amb MySQL</p>\n";
  	} else {
  		if ($_REQUEST['paraules'] == "") {
  			echo "<p class=\"advertencia\">La cerca ha de contenir com a mínim una paraula</p>\n";
  		} else {
  			$paraulescerca = str_replace("'", "''", $_REQUEST['paraules']);
			$textconsulta = "SELECT * FROM fitxa WHERE MATCH (titol, autoria, editor_literari, ".
				"entitat, editorial, lloc, materies, notes) AGAINST ('".$paraulescerca."');";
			//echo $textconsulta;
			mostrar_fitxes($textconsulta, True);
		}
	}
	include "app_peu.php";

?>

</body>

</html>