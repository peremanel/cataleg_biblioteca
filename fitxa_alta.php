<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Alta de nova fitxa
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

	echo "<div class=\"item\"><h2>Alta de nova fitxa</h2></div>";
	switch ($_REQUEST['accio']) {
		case 'alta':
			alta_fitxa();
			break;
		case 'cancel·lar':
			header("Location:index.php");
		default:
			formulari_alta();
			break;
	}


	function alta_fitxa() {
		// càrrega de constants
		include 'app_config.php';
		$connexio = mysqli_connect($host,$user,$password,$dbase);

		$titol_e = mysqli_real_escape_string($connexio, $_REQUEST['titol']);
		$autoria_e = mysqli_real_escape_string($connexio, $_REQUEST['autoria']);
		$editor_literari_e = mysqli_real_escape_string($connexio, $_REQUEST['editor_literari']);
		$entitat_e = mysqli_real_escape_string($connexio, $_REQUEST['entitat']);
		$editorial_e = mysqli_real_escape_string($connexio, $_REQUEST['editorial']);
		$edicio_e = mysqli_real_escape_string($connexio, $_REQUEST['edicio']);
		$lloc_e = mysqli_real_escape_string($connexio, $_REQUEST['lloc']);
		$descripcio_fisica_e = mysqli_real_escape_string($connexio, $_REQUEST['descripcio_fisica']);
		$suport_e = mysqli_real_escape_string($connexio, $_REQUEST['suport']);
		$materies_e = mysqli_real_escape_string($connexio, $_REQUEST['materies']);
		$tipus_e = mysqli_real_escape_string($connexio, $_REQUEST['tipus']);
		$notes_e = mysqli_real_escape_string($connexio, $_REQUEST['notes']);
		$data1 = inserir_data_sql($_REQUEST['data_catalogacio']);

		$textconsulta="INSERT INTO fitxa VALUES (NULL, '".$titol_e."', '".$autoria_e.
			"', '".$editor_literari_e."', '".$entitat_e."', '".$editorial_e."', '".$edicio_e."', '".$lloc_e.
			"', '".$_REQUEST['data']."', '".$descripcio_fisica_e."', '".$suport_e."', '".$materies_e.
			"', '".$tipus_e."', '".$_REQUEST['idioma']."', '".$_REQUEST['ISBN']."', '".$_REQUEST['CDU'].
			"', '".$notes_e."', '".$_REQUEST['catalogadora']."', ".$data1.", '".$_REQUEST['exportable']."');";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			$nou_id_fitxa = mysqli_insert_id($connexio);
			echo "<p class=\"notificacio\">Fitxa donada d'alta correctament amb el núm. ".$nou_id_fitxa."</p>";
			echo "<form action=\"fitxa.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$nou_id_fitxa."\" name=\"id_fitxa\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error 05: No es pot donar d'alta la fitxa</p>";
		}
		mysqli_free_result($result);				
	}



	function formulari_alta() {
	
		$ara = date("Y-m-d");

		echo "<div class=\"item\">\n";
		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Títol:</div>";
	  	echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"titol\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Autoria:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"autoria\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Editor literari:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"editor_literari\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Entitat:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"entitat\"></div></div>\n";

  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Editorial:</div>";
	  	echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"255\" name=\"editorial\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Edició:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"50\" name=\"edicio\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Lloc de publicació:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"100\" name=\"lloc\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Data de publicació:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=20 name=\"data\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Descripció física:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"descripcio_fisica\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Suport:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=20 maxlength=\"50\" name=\"suport\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Matèries:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"materies\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Tipus:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"50\" name=\"tipus\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Idioma:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"100\" name=\"idioma\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">ISBN:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=30 maxlength=\"100\" name=\"ISBN\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Sign.topogr.:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"50\" name=\"CDU\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Notes:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 name=\"notes\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Catalogat per:</div>";
		echo "<div class=\"valor\"><input type=\"text\" size=90 maxlength=\"30\" name=\"catalogadora\"></div></div>\n";

	  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Catalogat el:</div>";
		echo "<div class=\"valor\"><input type=\"date\" value=\"".$ara.
			"\" size=20 name=\"data_catalogacio\"></div></div>\n";

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

		</div>
	</div>
</div>

</body>

</html>