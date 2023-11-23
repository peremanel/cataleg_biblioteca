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
		$textconsulta = "INSERT INTO acces VALUES('a03512', 2);";
		if ($result = mysqli_query($connexio, $textconsulta)) {
			echo "inserida bé";
		}
		mysqli_free_result($result);
	}

?>