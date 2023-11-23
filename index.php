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
	Data: 14/09/2020
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
			<form action="fitxa_cerca2.php" method="POST">
			<h3>Cerca per camps</h3>
			<div class="entrada"><div class="etiqueta">Títol</div>
			<div class="valor"><input type="text" size=60 name="titol"></div></div>
			<div class="entrada"><div class="etiqueta">Autoria/Ed. literari</div>
			<div class="valor"><input type="text" size=60 name="autoria"></div></div>
			<div class="entrada"><div class="etiqueta">Matèries</div>
			<div class="valor"><input type="text" size=60 name="materies"></div></div>
			<div class="entrada"><div class="etiqueta">Editorial</div>
			<div class="valor"><input type="text" size=60 name="editorial"></div></div>
			<div class="entrada"><div class="etiqueta">Idioma</div>
			<div class="valor"><input type="text" size=60 name="idioma"></div></div>
			<div class="entrada"><div class="etiqueta">ISBN</div>
			<div class="valor"><input type="text" size=60 name="ISBN"></div></div>
			<div class="entrada"><div class="etiqueta">Sign.topogr.</div>
			<div class="valor"><input type="text" size=60 name="cdu"></div></div>
			<div class="entrada"><div class="etiqueta">Núm. de registre</div>
			<div class="valor"><input type="number" size=60 name="id_exemplar"></div></div>
			<br>
			<input type="submit" value="cerca">
			</form>
			<br>

		<?php
			include "app_peu.php";
		?>

</body>

</html>