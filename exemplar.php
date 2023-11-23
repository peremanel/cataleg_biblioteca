<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Pàgina principal de gestió d'exemplars
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

	echo "<div class=\"item\"><h2>Número de registre ".$_REQUEST['id_exemplar']."</h2></div>";
	$connexio = mysqli_connect($host,$user,$password,$dbase);
	if (mysqli_connect_errno())	{
  		echo "<p class=\"error\">Error 01: No ha pogut connectar amb MySQL</p>\n";
  	} else {
		switch ($_REQUEST['accio']) {
			case 'confirmar':
				eliminar_exemplar();
				break;
			case 'actualitzar':
				actualitzar_exemplar();
				break;
			case 'avortar':
				//tornar a exemplar.php
				tornar("?id_fitxa=".$_REQUEST['id_fitxa']."&id_exemplar=".$_REQUEST['id_exemplar']);
				break;
			case 'reassignar':
				//assignar l'exemplar a una altra fitxa
				reassignar_exemplar(1);
				break;
			case 'comprovar':
				//mostra la possible fitxa nova
				reassignar_exemplar(2);
				break;
			case 'assignar':
				//fa el canvi a la base de dades
				reassignar_exemplar(3);
				break;
			default:
				formulari_exemplar();
				if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
					mostrar_prestecs($_REQUEST['id_exemplar']);
				}
	  			break;
		}
	}
	include "app_peu.php";


	function formulari_exemplar() {
		global $connexio, $privilegi_avancat;

		$textconsulta = "SELECT * FROM exemplar WHERE id_exemplar=".$_REQUEST['id_exemplar'].";";
		//echo $textconsulta;
		if ($result = mysqli_query($connexio, $textconsulta)) {

			// Primer mostra les dades de la fitxa catalogràfica
			$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";
			mostrar_fitxes($textconsulta, False);

			echo "<div class=\"item\">\n";
  			$fila =mysqli_fetch_row($result);

			echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

			echo "<input type=\"hidden\" value=\"".$fila[0]."\" name=\"id_fitxa\">\n";
			echo "<input type=\"hidden\" value=\"".$fila[1]."\" name=\"id_exemplar\">\n";
	  		
	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Fons:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[2]).
				  			"\" size=90 maxlength=\"50\" name=\"fons\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Ubicació:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[3]).
				  			"\" size=90 maxlength=\"50\" name=\"ubicacio\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Signatura topogràfica:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[4]).
				  			"\" size=90 maxlength=\"50\" name=\"signatura_topografica\"></div></div>\n";

	  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Estat:</div>";
		  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[9]).
				  			"\" size=90 maxlength=\"20\" name=\"estat\"></div></div>\n";

			if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Procedència:</div>";
			  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[5]).
					  			"\" size=90 maxlength=\"50\" name=\"procedencia\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Preu en PTA:</div>";
			  	echo "<div class=\"valor\"><input type=\"number\" value=\"".$fila[6].
					  			"\" size=90 name=\"preu_pta\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Preu en EUR:</div>";
			  	echo "<div class=\"valor\"><input type=\"number\" step=\"any\" value=\"".$fila[7].
					  			"\" size=90 name=\"preu_eur\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Baixa:</div>";
			  	echo "<div class=\"valor\"><input type=\"date\" value=\"".htmlspecialchars($fila[8]).
					  			"\" size=20 name=\"baixa\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Notes:</div>";
			  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[10]).
					  			"\" size=90 name=\"notes\"></div></div>\n";

		  		echo "<div class=\"entrada\"><div class=\"etiqueta\">Exportable:</div>";
			  	echo "<div class=\"valor\"><input type=\"text\" value=\"".htmlspecialchars($fila[11]).
					  			"\" size=90 name=\"exportable\"></div></div>\n";

				echo "<br>\n";
				echo "<div class=\"entrada\"><div class=\"etiqueta\"></div>";
		  		echo "<div class=\"valor\">";
				if ($_REQUEST['accio'] == "eliminar") {
					echo "<p class=\"advertencia\">Confirmau eliminació de l'exemplar?</p><br>\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"confirmar\">\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"avortar\">\n";
				} else {
					echo "<input type=\"submit\" name=\"accio\" value=\"actualitzar\">\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"eliminar\">\n";
					echo "<input type=\"submit\" name=\"accio\" value=\"reassignar\">\n";
				}
				echo "</div></div>";
			}
			echo "</form></div>\n";
			mysqli_free_result($result);
  		}
  		else {
  			echo "<p class=\"error\">Error 02: No es pot accedir al catàleg de bibilioteca</p>\n";
  			echo  $cadenaconsulta;
  		}
  		echo "</div>";
	}


	
	function actualitzar_exemplar() {
		// modifica els valors a la base de dades
		global $connexio;

		$fons_e = mysqli_real_escape_string($connexio, $_REQUEST['fons']);
		$ubicacio_e = mysqli_real_escape_string($connexio, $_REQUEST['ubicacio']);
		$signatura_topografica_e = mysqli_real_escape_string($connexio, $_REQUEST['signatura_topografica']);
		$procedencia_e = mysqli_real_escape_string($connexio, $_REQUEST['procedencia']);
		$estat_e = mysqli_real_escape_string($connexio, $_REQUEST['estat']);
		$notes_e = mysqli_real_escape_string($connexio, $_REQUEST['notes']);
		$data1 = inserir_data_sql($_REQUEST['baixa']);

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
		
		$textconsulta = "UPDATE exemplar SET fons='".$fons_e."', ubicacio='".$ubicacio_e.
			"', signatura_topografica='".$signatura_topografica_e."', procedencia='".$procedencia_e.
			"', preu_pta=".$pta.", preu_eur=".$eur.", baixa=".$data1.
			", estat='".$estat_e."', notes='".$notes_e."', exportable='".$_REQUEST['exportable'].
			"' WHERE id_exemplar=".$_REQUEST['id_exemplar'].";";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Exemplar actualitzat correctament</p>";
			echo "<form action=\"fitxa.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error 05: No es pot actualitzar l'exemplar</p>";
		}
		mysqli_free_result($result);
	}



	function eliminar_exemplar() {
		// elimina l'exemplar
		global $connexio;
		
		echo "<div class=\"item\">";
		$textconsulta = "DELETE FROM exemplar WHERE id_exemplar=".$_REQUEST['id_exemplar'].";";
		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Exemplar eliminat.</p>";
		} else {
			echo "<p class=\"error\">Error 06: No s'ha pogut eliminar</p>";
		}
		echo "<form action=\"index.php\" method=\"POST\">";
		echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		echo "</form></div>";
		mysqli_free_result($result);
	}



	function reassignar_exemplar($pas) {
		// assigna el títol actual a una altra fitxa
		global $connexio;
		$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";

		echo "<div class=\"item\">\n";
  		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";
		echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
		
		switch ($pas) {
			case '1':
				// introduir la nova fitxa
				echo "<div class=\"item\">Fitxa actual:</div>";
				mostrar_fitxes($textconsulta, False);
				echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
				echo "<div class=\"item\">\n";
				echo "<div class=\"entrada\"><div class=\"etiqueta\">Nova fitxa:</div>";
		  		echo "<div class=\"valor\"><input type=\"number\" value=\"".$nou_id_fitxa.
				  			"\" size=90 name=\"nou_id_fitxa\"></div></div>\n";
				echo "<br><input type=\"submit\" name=\"accio\" value=\"comprovar\">\n";	
				break;

			case '2':
				// comprovar la nova fitxa
				echo "<div class=\"item\">Fitxa actual:</div>";
				mostrar_fitxes($textconsulta, False);
				$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['nou_id_fitxa'].";";
				echo "<div class=\"item\">Fitxa nova:</div>";
				echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
				mostrar_fitxes($textconsulta, False);
				echo "<input type=\"hidden\" value=\"".$_REQUEST['nou_id_fitxa'].
					"\" name=\"nou_id_fitxa\">\n";
				echo "<br><input type=\"submit\" name=\"accio\" value=\"assignar\">\n";
				echo "<input type=\"submit\" name=\"accio\" value=\"cancel·lar\">\n";
				break;
			case '3':
				// assignar la nova fitxa
				//$_REQUEST['id_fitxa'] = $_REQUEST['nou_id_fitxa'];
				$textconsulta ="UPDATE exemplar SET id_fitxa=".$_REQUEST['nou_id_fitxa'].
					" WHERE id_exemplar=".$_REQUEST['id_exemplar'];
				if ($result=mysqli_query($connexio, $textconsulta)) {
					echo "<p class=\"notificacio\">Exemplar reassignat satisfatòriament</p>";
				} else {
					echo $textconsulta;
					echo "<p class=\"error\">Error: No s'ha pogut reassignar</p>";
				}
				echo "<form action=\"exemplar.php\" method=\"POST\">";
				echo "<input type=\"hidden\" value=\"".$_REQUEST['nou_id_fitxa']."\" name=\"id_fitxa\">\n";
				echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
				echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
				echo "</form></div>";
				mysqli_free_result($result);					
				break;
		}
		echo "</form></div>\n";
	}


	function mostrar_prestecs($id_exemplar) {
		//mostra tots els exemplars d'una determinada fitxa catalogràfica
		global $connexio;

		$textconsulta = "SELECT * FROM prestec WHERE id_exemplar=".$id_exemplar;
		if ($result=mysqli_query($connexio, $textconsulta)) {
			if (mysqli_num_rows($result)>0) {
				echo "<br><div class=\"item\">";
				echo "<div class=\"entrada\"><div class=\"etiqueta\"><h3>Préstecs:</h3></div>";
				echo "<div class=\"valor\"><table>\n";
	  			echo "<tr><th>Data préstec</th><th>Prestatari</th><th>Data devolució</th><th>Notes</th></tr>\n";
				while ($fila =mysqli_fetch_row($result)){
					if ($fila[3] == "") {
						// préstec viu
						echo "<tr class=\"prestec_viu\">";
					} else {
						echo "<tr>";
					}
					echo "<td>".$fila[1]."</td>";
					echo "<td>".$fila[2]."</td>";
					echo "<td>".$fila[3]."</td>";
					echo "<td>".$fila[4]."</td>";
					echo "</tr>\n";
				}
				echo "</table></div></div>\n";
			}
		} else {
			echo "<p class=\"error\">Error: No es poden recuperar els préstecs</p>";
		}

		//botons per operar els préstecs
		echo "<br><div class=\"item\">";
		echo "<form action=\"prestec.php\" method=\"POST\" id=\"formulari3\">";
		echo "<input type=\"hidden\" value=\"".$id_exemplar."\" name=\"id_exemplar\">\n";
		echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
		echo "<div class=\"entrada\"><div class=\"etiqueta\"></div>";
		echo "<div class=\"valor\">";
		$textconsulta = "SELECT * FROM prestec WHERE id_exemplar=".$id_exemplar." AND data_devolucio IS NULL;";
		if ($result=mysqli_query($connexio, $textconsulta)) {
			if (mysqli_num_rows($result)>0) {
				echo "<input type=\"submit\" name=\"accio\" value=\"devolució\">";
			} else {
				echo "<input type=\"submit\" name=\"accio\" value=\"préstec\">";
			}
		}
		echo "</div></div></form>\n";
	}
?>

</body>

</html>