<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Gestió de fitxes
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

	session_start();

	echo "<div class=\"item\"><h2>Fitxa núm. ".$_REQUEST['id_fitxa']."</h2></div>";
	$connexio = mysqli_connect($host,$user,$password,$dbase);
	if (mysqli_connect_errno())	{
  		echo "<p class=\"error\">Error 01: No ha pogut connectar amb MySQL</p>\n";
  	} else {
		switch ($_REQUEST['accio']) {
			case 'confirmar':
				eliminar_fitxa();
				break;
			case 'actualitzar':
				actualitzar_fitxa();
				break;
			case 'avortar':
				//tornar a index.php
				tornar("?id_fitxa=".$_REQUEST['id_fitxa']);
				break;
			default:
				formulari_fitxa();
	  			break;
		}
	}
	include "app_peu.php";


	function formulari_fitxa() {
		global $connexio, $privilegi_avancat;

		$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";
		//echo $textconsulta;
		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<div class=\"item\">\n";
  			$fila =mysqli_fetch_row($result);

			echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

			echo "<input type=\"hidden\" value=\"".$fila[0]."\" name=\"id_fitxa\">\n";
	  		
		  	casella_textarea("Títol", "titol", $fila[1]);
		  	casella_textarea("Autoria", "autoria", $fila[2]);
		  	casella_textarea("Editor literari", "editor_literari", $fila[3]);
		  	casella_textarea("Entitat", "entitat", $fila[4]);

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Editorial:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[5]).
				  			"\" size=90 maxlength=\"255\" name=\"editorial\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Edició:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[6]).
				  			"\" size=90 maxlength=\"50\" name=\"edicio\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Lloc de publicació:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[7]).
				  			"\" size=90 maxlength=\"100\" name=\"lloc\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Data de publicació:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[8]).
				  			"\" size=20 name=\"data\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Descripció física:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[9]).
				  			"\" size=90 name=\"descripcio_fisica\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Suport:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[10]).
				  			"\" size=20 maxlength=\"50\" name=\"suport\"></div></div>\n";

		  	casella_textarea("Matèries", "materies", $fila[11]);

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Tipus:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[12]).
				  			"\" size=90 maxlength=\"50\" name=\"tipus\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Idioma:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[13]).
				  			"\" size=90 maxlength=\"100\" name=\"idioma\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">ISBN:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[14]).
				  			"\" size=30 maxlength=\"100\" name=\"ISBN\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Sign.topogr.:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[15]).
				  			"\" size=90 maxlength=\"50\" name=\"CDU\"></div></div>\n";

		  	casella_textarea("Notes", "notes", $fila[16]);

			if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
				// usuari amb privilegis
		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Catalogat per:</div>";
			  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[17]).
					  			"\" size=90 maxlength=\"30\" name=\"catalogadora\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Catalogat el:</div>";
			  	echo "<div class=\"valor\"><input type=\"date\" value=\"".htmlspecialchars($fila[18]).
					  			"\" size=20 name=\"data_catalogacio\"></div></div>\n";
			}

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Exportable:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[19]).
				  			"\" size=20 maxlength=\"10\" name=\"exportable\"></div></div>\n";

	/*					echo "<div class=\"etiqueta\">Text certificat:</div>";	
						echo "<div class=\"valor\"><textarea rows=\"4\" cols=\"65\" name=\"text_certificat_catala\"".
							" form=\"formulari\">".htmlspecialchars($fila[8])."</textarea></div><br>\n";

	*/
	  		//botons
			if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
				// usuari amb privilegis
				echo "<br>\n";
				echo "<div class=\"entrada\"><div class=\"etiqueta\"></div>";
		  		echo "<div class=\"valor\">";
				if ($_REQUEST['accio'] == "eliminar") {
					echo "<p class=\"advertencia\">Confirmau eliminació de la fitxa. També s'eliminaran els exemplars</p><br>\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"confirmar\">\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"avortar\">\n";
				} else {
					echo "<input type=\"submit\" name=\"accio\" value=\"actualitzar\">\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"eliminar\">\n";
				}
				echo "</div></div>";
			}
			echo "</form></div>\n";
			mostrar_exemplars($_REQUEST['id_fitxa']);
			mysqli_free_result($result);
  		}
  		else {
  			echo "<p class=\"error\">Error 02: No es pot accedir al catàleg de bibilioteca</p>\n";
  			echo  $cadenaconsulta;
  		}
  		echo "</div>";
	}


	function mostrar_exemplars($id_fitxa) {
		//mostra tots els exemplars d'una determinada fitxa catalogràfica
		global $connexio, $privilegi_avancat;

		$textconsulta = "SELECT * FROM exemplar WHERE id_fitxa=".$id_fitxa;
		if ($_SESSION['bib_nivell'] > $privilegi_avancat) {
			$textconsulta .= " AND baixa IS NULL;";
		} else {
			$textconsulta .= ";";
		}
		if ($result=mysqli_query($connexio, $textconsulta)) {
			if (mysqli_num_rows($result)>0) {
				echo "<br><div class=\"item\">";
				echo "<div class=\"entrada\"><div class=\"etiqueta\"><h3>Exemplars:</h3></div></div>";

//				echo "<div class=\"valor\"><table>\n";
				echo "<table>";
	  			echo "<tr><th>Número de registre</th><th>Fons</th><th>Ubicació</th><th>Signatura topogràfica</th>".
	  				"<th>Estat</th>";
				if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
					echo "<th>Procedència</th><th>Data baixa</th><th>Exportable</th>";
				}
	  			echo "</tr>\n";
				while ($fila =mysqli_fetch_row($result)){
					echo "<tr><td><a href=\"exemplar.php?id_exemplar=".$fila[1]."&id_fitxa=".$fila[0]."\">".$fila[1]."</a></td>";
					echo "<td>".$fila[2]."</td>";
					echo "<td>".$fila[3]."</td>";
					echo "<td>".$fila[4]."</td>";
					echo "<td>".$fila[9]."</td>";
					if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
						echo "<td>".$fila[5]."</td>";
						echo "<td>".$fila[8]."</td>";
						echo "<td>".$fila[11]."</td>";
					} 
					echo "</tr>";
				}
				echo "</table>";
//				echo "</table></div></div>\n";
			}
			if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
				echo "<br><div class=\"item\">";
				echo "<form action=\"exemplar_alta.php\" method=\"POST\" id=\"formulari2\">";
				echo "<input type=\"hidden\" value=\"".$id_fitxa."\" name=\"id_fitxa\">\n";
				echo "<div class=\"entrada\"><div class=\"etiqueta\"></div>";
				echo "<div class=\"valor\"><input type=\"submit\" name=\"accio\" value=\"afegir exemplar\">";
				echo "</div></div></form>\n";
			}
		}
	}


	function actualitzar_fitxa() {
		// modifica els valors a la base de dades
		global $connexio;
		
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

		$textconsulta="UPDATE fitxa SET titol='".$titol_e."', autoria='".$autoria_e.
			"', editor_literari='".$editor_literari_e."', entitat='".$entitat_e.
			"', editorial='".$editorial_e."', edicio='".$edicio_e."', lloc='".$lloc_e.
			"', data='".$_REQUEST['data']."', descripcio_fisica='".$descripcio_fisica_e.
			"', suport='".$suport_e."', materies='".$materies_e."', tipus='".$tipus_e.
			"', idioma='".$_REQUEST['idioma']."', ISBN='".$_REQUEST['ISBN'].
			"', CDU='".$_REQUEST['CDU']."', notes='".$notes_e."', catalogadora='".$_REQUEST['catalogadora'].
			"', data_catalogacio=".$data1."', exportable='".$_REQUEST['exportable']."' ".
			" WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Fitxa actualitzada correctament</p>";
			echo "<form action=\"fitxa.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";

		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error 05: No es pot actualitzar la fitxa</p>";
		}
		mysqli_free_result($result);
	}


	function eliminar_fitxa() {
		// elimina la fitxa i els exemplars associats
		global $connexio;
		echo "<div class=\"item\">";
		$textconsulta = "DELETE FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";
		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Fitxa eliminada.</p>";
		} else {
			echo "<p class=\"error\">Error 06: No s'ha pogut eliminar</p>";
		}
		echo "<form action=\"index.php\" method=\"GET\">";
		echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		echo "</form></div>";
		mysqli_free_result($result);
	}


	function casella_textarea($etiq, $camp, $contingut) {
		// proporciona el codi de sortida d'una textarea ajustada
		// a la mida del contingut
		$cols = 90;
		$rows = floor(strlen($contingut) / $cols);
		if ($rows == 0) $rows=1;
		echo "<div class=\"entrada\"><div class=\"etiqueta\">".$etiq.":</div>";
		echo "<div class=\"valor\"><textarea name=\"".$camp."\" form=\"formulari\" rows=".$rows." cols=87 >".
		htmlspecialchars($contingut)."</textarea></div></div>\n";
	}

?>

</body>

</html>