<?php

	/* app_acces.php
	Obté el nivell d'accés a aplicació d'intranet
	Catàleg de la Biblioteca
	
	Fundació Pilar i Joan Miró a Mallorca
	Pere Manel Mulet Ferrer
	
	Versió 0.1
	23/5/2018 */

	// Obté el nom d'usuari i assigna el nivell 
	// segons indica la taula de privilegis

	include "app_config.php";
	
	session_start();
	$connexio = mysqli_connect($host,$user,$password,$dbase);
	if (!mysqli_connect_errno()) {
		$textconsulta = "SELECT nivell FROM acces WHERE usuari='".$_SESSION['usuari']."';";
		if ($result = mysqli_query($connexio, $textconsulta)) {
			if (mysqli_num_rows($result) == 1) {
				$fila =mysqli_fetch_row($result);
				$_SESSION['bib_nivell'] = $fila[0];	
			} else {
				$_SESSION['bib_nivell'] = $privilegi_minim;
			}
		
		}
		// registre de l'usuari
		if ($log_activat == True) {
			$textconsulta = "INSERT INTO log VALUES (CURRENT_TIME(), '".$_SESSION['usuari']."', '".
				basename($_SERVER['PHP_SELF'])."', NULL);";
			mysqli_query($connexio, $textconsulta);
		}
		mysqli_free_result($result);
	}

?>