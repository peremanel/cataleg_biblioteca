<?php
	/* appconfig.php
	Constants i variables generals
	Aplicació de la intranet Gestió del catàleg de biblioteca
	
	Fundació Pilar i Joan Miró a Mallorca
	Pere Manel Mulet Ferrer
	
	Versió 0.2
	27/12/2019 */


	// Accés a la base de dades
	$host = "localhost";
	$user = "appuser";
	$password = "v6Q3_K45";
	$dbase = "cataleg_biblioteca";


	// Configuració de informació per pàgina
	// no s'utilitza per ara
	$files_per_pagina = 10;
	$max_items_navegacio = 20;


	// privilegis
	//nivell de privilegi per a accions avançades
	$privilegi_avancat = 2;
	// nivell de privilegi per a consulta
	$privilegi_consulta = 99;
	// nivell d'accés per a usuaris de consulta
	$privilegi_minim = 90;


	// seguiment d'accés dels usuaris
	$log_activat = False; 
	
	// pàgina on redirigeix si l'usuari no té privilegis
	$pagina_rebuig = "https://www.miromallorca.com";

?>