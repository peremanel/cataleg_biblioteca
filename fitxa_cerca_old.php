<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Mostra el resultat de la cerca de fitxes
	Catàleg de biblioteca
	Fundació Pilar i Joan Miró a Mallorca
	Versió 0.2
	Data: 23/5/2018
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
  		if ($_REQUEST['titol'].$_REQUEST['autoria'].$_REQUEST['materies'].
  			$_REQUEST['editorial'].$_REQUEST['ISBN'] == "") {
  			echo "<p class=\"advertencia\">La cerca ha de contenir com a mínim un criteri</p>\n";
  		} else {
			$clausula = construeix_clausula_cerca();
			$textconsulta = "SELECT id_fitxa, autoria, data, titol, edicio, editor_literari, lloc, editorial ".
				"FROM fitxa ".$clausula.";";
			//echo $textconsulta;
			mostrar_fitxes($textconsulta, True);
		}
	}

	function construeix_clausula_cerca() {
		// Retorna la clàsula WHERE de la consulta SQL
		$rossega = " WHERE ";
		$addicional = "";
		if ($_REQUEST['titol'] != "") {
			$titolcerca = str_replace("'", "''", $_REQUEST['titol']);
			$rossega .= "titol LIKE '%".$titolcerca."%'";
			$addicional = " AND ";
		}
		if ($_REQUEST['autoria'] != "") {
			$autoriacerca = str_replace("'", "''", $_REQUEST['autoria']);
			$rossega .= $addicional."autoria LIKE '%".$autoriacerca."%'";
			$addicional = " AND ";
		}
		if ($_REQUEST['materies'] != "") {
			$materiescerca = str_replace("'", "''", $_REQUEST['materies']);
			$rossega .= $addicional."materies LIKE '%".$materiescerca."%'";
			$addicional = " AND ";
		}
		if ($_REQUEST['editorial'] != "") {
			$editorialcerca = str_replace("'", "''", $_REQUEST['editorial']);
			$rossega .= $addicional."editorial LIKE '%".$editorialcerca."%'";
		}
		if ($_REQUEST['ISBN'] != "") {
			$rossega .= $addicional."ISBN LIKE '%".$_REQUEST['ISBN']."%'";
		}
		$rossega .= " ORDER BY autoria, titol";
		return $rossega;
	}

	include "app_peu.php";

?>

</body>

</html>