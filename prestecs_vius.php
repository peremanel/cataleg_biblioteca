<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Mostra els préstecs en vigor
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

	echo "<div class=\"item\"><h2>Préstecs en vigor</h2></div>";
	$connexio = mysqli_connect($host,$user,$password,$dbase);
	if (mysqli_connect_errno())	{
  		echo "<p class=\"error\">Error 01: No ha pogut connectar amb MySQL</p>\n";
  	} else {
  		$textconsulta = "SELECT exemplar.id_fitxa, prestec.* FROM prestec ".
  			"INNER JOIN exemplar ON prestec.id_exemplar=exemplar.id_exemplar ".
  			"WHERE prestec.data_devolucio IS NULL ORDER BY prestec.prestatari;";
  		echo "<div class=\"item\"></div>";
  		if ($result = mysqli_query($connexio, $textconsulta)) {
  			$prestatari_ant = "";
			while ($fila =mysqli_fetch_row($result)){
				$prestatari_act = $fila[3];
				if ($prestatari_ant != $prestatari_act) {
					echo "<hr><h3>".$prestatari_act."</h3>";
					$prestatari_ant = $prestatari_act;
				}
				mostrar_fitxes("SELECT * FROM fitxa WHERE id_fitxa=".$fila[0], False);
				echo "<div class=\"item\"><ul><li>Exemplar amb núm. de registre <a href=\"exemplar.php?id_exemplar=".$fila[1]."&id_fitxa=".$fila[0]."\">".$fila[1]."</a>: prestat dia ";
				echo $fila[2].". Notes: ";
				echo $fila[5]."</li></ul></div>\n";
			}
		} else {
			echo "<p class=\"error\">Error: No pot recuperar préstecs</p>\n";
		}
		mysqli_free_result($result);
	}

	include "app_peu.php";
		
?>

</body>

</html>