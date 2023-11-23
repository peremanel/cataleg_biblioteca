<!DOCTYPE html>
<html>

<?php
	include "app_html_head.php";
?>

<body>

<!--
	Pàgina principal del catàleg de biblioteca
	Fundació Pilar i Joan Miró a Mallorca
	Versió 0.1
	Data: 23/05/2018
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

?>

		<h2>Cerca dins tot el catàleg</h2><br>

			<!-- Text complet -->
			<form action="fitxa_fulltext.php" method="POST">
			<h3>Cerca per text complet</h3>
			<div class="entrada"><div class="etiqueta">Paraules</div>
			<div class="valor"><input type="text" size=60 name="paraules"></div></div>
			<br>
			<input type="submit" value="cerca">
			</form>
			<br><hr>

			<!-- Per camps -->
			<form action="fitxa_cerca.php" method="POST">
			<h3>Cerca per camps</h3>
			<div class="entrada"><div class="etiqueta">Títol</div>
			<div class="valor"><input type="text" size=60 name="titol"></div></div>
			<div class="entrada"><div class="etiqueta">Autoria</div>
			<div class="valor"><input type="text" size=60 name="autoria"></div></div>
			<div class="entrada"><div class="etiqueta">Matèries</div>
			<div class="valor"><input type="text" size=60 name="materies"></div></div>
			<div class="entrada"><div class="etiqueta">Editorial</div>
			<div class="valor"><input type="text" size=60 name="editorial"></div></div>

			<?php
				//opcions bibliotecària
				//recerca també per ISBN
				include 'app_config.php';
				session_start();
				if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
					echo "<div class=\"entrada\"><div class=\"etiqueta\">ISBN</div>\n";
					echo "<div class=\"valor\"><input type=\"text\" size=60 name=\"ISBN\"></div></div>";
				}
			?>

			<br>
			<input type="submit" value="cerca">
			</form>
			<br><hr>
		
		<?php
			//opcions bibiliotecària
			// recerca també per número d'exemplar
			include 'app_config.php';
			session_start();
			if ($_SESSION['bib_nivell'] <= $privilegi_avancat) {
				//echo "<hr><br>\n";
				echo "<form action=\"exemplar_cerca.php\" method =\"POST\">\n";
					echo "<h3>Cerca específica</h3>\n";
					echo "<div class=\"entrada\"><div class=\"etiqueta\">Número de fitxa</div>\n";
					echo "<div class=\"valor\"><input type=\"number\" size=40 name=\"id_fitxa\"></div></div>";
					echo "<div class=\"entrada\"><div class=\"etiqueta\">Número d'exemplar</div>\n";
					echo "<div class=\"valor\"><input type=\"number\" size=40 name=\"id_exemplar\"></div></div>";
					echo "<div class=\"entrada\"><div class=\"etiqueta\">CDU</div>\n";
					echo "<div class=\"valor\"><input type=\"text\" size=60 name=\"cdu\"></div></div>";
					echo "<br><input type=\"submit\" value=\"cerca\">\n";
				echo "</form>\n";
			}
		?>

		<?php
			include "app_peu.php";
		?>

</body>

</html>