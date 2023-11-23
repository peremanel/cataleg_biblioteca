<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Alta d'exemplar
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
	if ($_SESSION['bib_nivell'] > $privilegi_avancat) {
		header("Location:".$pagina_rebuig);
	}
?>

<?php
	// Càrrega de constants
	include 'app_config.php';

	// CAPÇALERA
	include "app_capsalera.php";
	
	// COS PRINCIPAL
	
	// Càrrega de constants i paràmetres
	include 'app_funcions.php';
	session_start();

	echo "<div class=\"item\"><h2>Alta d'un nou exemplar</h2></div>";
	switch ($_REQUEST['accio']) {
		case 'alta':
			alta_exemplar();
			break;
		case 'cancel·lar':
			header("Location:index.php");
		default:
			formulari_alta();
			break;
	}
	include "app_peu.php";


	function alta_exemplar() {
		// càrrega de constants
		include 'app_config.php';

		$connexio = mysqli_connect($host,$user,$password,$dbase);

		$fons_e = mysqli_real_escape_string($connexio, $_REQUEST['fons']);
		$ubicacio_e = mysqli_real_escape_string($connexio, $_REQUEST['ubicacio']);
		$signatura_topografica_e = mysqli_real_escape_string($connexio, $_REQUEST['signatura_topografica']);
		$procedencia_e = mysqli_real_escape_string($connexio, $_REQUEST['procedencia']);
		$estat_e = mysqli_real_escape_string($connexio, $_REQUEST['estat']);
		$notes_e = mysqli_real_escape_string($connexio, $_REQUEST['notes']);
		if ($_REQUEST['preu_pta'] == "") {
			$pta = "NULL";
		} else {
			$pta = $_REQUEST['preu_pta'];
		}
		if ($_REQUEST['preu_eur'] == "") {
			$eur = "NULL";
		} else {
			$eur = $_REQUEST['preu_eur'];
		}
		$textconsulta="INSERT INTO exemplar VALUES (".$_REQUEST['id_fitxa'].", NULL, '".$fons_e."', '".
			$ubicacio_e."', '".$signatura_topografica_e."', '".$procedencia_e."', ".$pta.", ".$eur.
			", NULL, '".$estat_e."', '".$notes_e."', '".$_REQUEST['exportable']."');";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			$nou_id_exemplar = mysqli_insert_id($connexio);
			echo "<p class=\"notificacio\">Exemplar donat d'alta correctament amb el número de registre ".$nou_id_exemplar."</p>";
			echo "<form action=\"fitxa.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error 05: No es pot donar d'alta l'exemplar</p>";
		}
		mysqli_free_result($result);				
	}


	function formulari_alta() {
	
		echo "<div class=\"item\">\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

		echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";

  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Fons:</div>";
	  	echo "<div class=\"valor\"><input type=\"text\" size=50 maxlength=\"50\" name=\"fons\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Ubicació:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=50 maxlength=\"50\" name=\"ubicacio\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Signatura topogràfica:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=50 maxlength=\"50\" name=\"signatura_topografica\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Procedència:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=50 maxlength=\"50\" name=\"procedencia\"></div></div>\n";

  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Preu (PTA):</div>";
	  	echo "<div class=\"valor\"><input type=\"number\" size=50 name=\"preu_pta\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Preu (EUR):</div>";
		echo "<div class=\"valor\"><input type=\"number\" step=\"any\" size=50 name=\"preu_eur\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Estat:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=20 maxlength=\"20\" name=\"estat\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Notes:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"notes\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Exportable:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=20 name=\"exportable\"></div></div>\n";

		//botons
		echo "<br>\n";
		echo "<div class=\"entrada\"><div class=\"etiqueta\"></div>";
  		echo "<div class=\"valor\">";
		echo "<input type=\"submit\" name=\"accio\" value=\"alta\">\n";
		echo "<input type=\"submit\" name=\"accio\" value=\"cancel·lar\">\n";
		echo "</div></div>";
		echo "</form>\n";
		echo "</div>";
	}

?>

</body>

</html>