<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Préstec d'exemplars
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
	
	// Càrrega de funcions
	include 'app_funcions.php';

	echo "<div class=\"item\"><h2>Exemplar amb número de registre ".$_REQUEST['id_exemplar']."</h2></div>";
	$connexio = mysqli_connect($host,$user,$password,$dbase);
	if (mysqli_connect_errno())	{
  		echo "<p class=\"error\">Error 01: No ha pogut connectar amb MySQL</p>\n";
  	} else {
		switch ($_REQUEST['accio']) {

			case 'préstec':
				//input préstec
				formulari_prestec();
	  			break;
			case 'alta':
				//alta prestec a la BD
				alta_prestec();
				break;
			case 'devolució':
				//input devolució
				formulari_devolucio();
				break;
			case 'confirmar':
				//alta devolució a la BD
				confirmar_devolucio();
				break;
			default:
				header("Location:exemplar.php?id_fitxa=".$_REQUEST['id_fitxa']."&id_exemplar=".$_REQUEST['id_exemplar']);
				break;
		}
	}



	function formulari_prestec() {
		// Dóna d'alta un nou préstec

		// Primer mostra les dades de la fitxa catalogràfica
		$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";
		mostrar_fitxes($textconsulta);
		$ara = date("Y-m-d");

		echo "<div class=\"item\">\n";
  		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
		  		
			echo "<div class=\"entrada\"><div class=\"etiqueta\">Data Préstec:</div>";
		  	echo "<div class=\"valor\"><input type=\"date\" value=\"".$ara.
					  			"\" size=40 name=\"data_prestec\"></div></div>\n";

		  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Prestatari:</div>";
			echo "<div class=\"valor\"><input type=\"text\" size=80 maxlength=\"50\" name=\"prestatari\"></div></div>\n";

		  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Notes:</div>";
			echo "<div class=\"valor\"><input type=\"text\" size=80 name=\"notes\"></div></div>\n";

			echo "<br><input type=\"submit\" name=\"accio\" value=\"alta\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"cancel·lar\">\n";
			
		echo "</form></div>\n";
	}


	function alta_prestec() {
		//insereix les dades a la base de dades
		include 'app_config.php';

		$connexio = mysqli_connect($host,$user,$password,$dbase);

		$notes_e = mysqli_real_escape_string($connexio, $_REQUEST['notes']);
		$data1 = inserir_data_sql($_REQUEST['data_prestec']);

		$textconsulta="INSERT INTO prestec VALUES (".$_REQUEST['id_exemplar'].", ".$data1.
			", '".$_REQUEST['prestatari']."', NULL, '".$notes_e."');";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Préstec donat d'alta</p>";
			echo "<form action=\"exemplar.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error: No es pot donar d'alta aquest préstec</p>";
		}
		mysqli_free_result($result);				
	}	


	function formulari_devolucio() {
		// només cal indicar la data de devolució i modificar les notes, si escau
		// Primer mostra les dades de la fitxa catalogràfica
		global $connexio;

		$textconsulta = "SELECT * FROM fitxa WHERE id_fitxa=".$_REQUEST['id_fitxa'].";";
		mostrar_fitxes($textconsulta);

		$textconsulta2 = "SELECT * FROM prestec WHERE id_exemplar=".$_REQUEST['id_exemplar']." AND data_devolucio IS NULL;";

		if ($result = mysqli_query($connexio, $textconsulta2)) {
			$ara = date("Y-m-d");
			$fila =mysqli_fetch_row($result);

			echo "<div class=\"item\">\n";
	  		echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\" id=\"formulari\">";

				echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
				echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
			  		
				echo "<div class=\"entrada\"><div class=\"etiqueta\">Data Préstec:</div>";
			  	echo "<div class=\"valor\"><input type=\"date\" value=\"".$fila[1].
						  			"\" size=40 name=\"data_prestec\"></div></div>\n";

			  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Prestatari:</div>";
				echo "<div class=\"valor\"><input type=\"text\" value=\"".$fila[2].
									"\" size=80 maxlength=\"50\" name=\"prestatari\"></div></div>\n";

			  	echo "<div class=\"entrada\"><div class=\"etiqueta\">Notes:</div>";
				echo "<div class=\"valor\"><input type=\"text\" value=\"".$fila[4].
									"\" size=80 name=\"notes\"></div></div>\n";

				echo "<div class=\"entrada\"><div class=\"etiqueta\">Data devolució:</div>";
			  	echo "<div class=\"valor\"><input type=\"date\" value=\"".$ara.
						  			"\" size=40 name=\"data_devolucio\"></div></div>\n";

				echo "<br><input type=\"submit\" name=\"accio\" value=\"confirmar\">\n";
				echo "<input type=\"submit\" name=\"accio\" value=\"cancel·lar\">\n";
				
			echo "</form></div>\n";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error: No es pot donar d'alta aquesta devolució</p>";			
		}
	}


	function confirmar_devolucio()  {
		// modifica els valors a la base de dades
		global $connexio;

		$prestatari_e = mysqli_real_escape_string($connexio, $_REQUEST['prestatari']);
		$notes_e = mysqli_real_escape_string($connexio, $_REQUEST['notes']);
		$data1 = inserir_data_sql($_REQUEST['data_prestec']);
		$data2 = inserir_data_sql($_REQUEST['data_devolucio']);

		$textconsulta = "UPDATE prestec SET data_prestec=".$data1.", prestatari='".$prestatari_e.
			"', data_devolucio=".$data2.", notes='".$notes_e.
			"' WHERE id_exemplar=".$_REQUEST['id_exemplar']." AND data_devolucio IS NULL;";

		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "<p class=\"notificacio\">Préstec retornat correctament</p>";
			echo "<form action=\"exemplar.php\" method=\"POST\">";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_fitxa']."\" name=\"id_fitxa\">\n";
			echo "<input type=\"hidden\" value=\"".$_REQUEST['id_exemplar']."\" name=\"id_exemplar\">\n";
			echo "<input type=\"submit\" name=\"accio\" value=\"continuar\">\n";
		  	echo "</form>";
		} else {
			echo $textconsulta;
			echo "<p class=\"error\">Error: No es pot registrar la devolució</p>";
		}
		mysqli_free_result($result);
	}
	
?>

		</div>
	</div>
</div>

</body>

</html>