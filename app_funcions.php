<?php
	/* Funcions d'ús divers
	de l'aplicació del catàleg de biblioteca
	Miró Mallorca Fundació */

	function mostrar_fitxes($cadenaconsulta, $mostranombre){
		// execució de la consulta

		global $connexio, $obri_notificacio, $pagines, $files_per_pagina;
		if ($result = mysqli_query($connexio, $cadenaconsulta)) {
      if ($mostranombre) {
			 echo "<div class=\"item\"><h2>".mysqli_num_rows($result)." resultats</h2></div>\n";
      }
      echo "<div class=\"item\">\n";
			$buit = True;
  			while ($fila =mysqli_fetch_row($result)) {
  				$buit = False;
  				echo "<p class=\"referencia\">";
  				echo "<a href=\"fitxa.php?id_fitxa=".$fila[0]."\">".$fila[0]."</a> ";
  				// autoria
  				if ($fila[1]!="") {
  					echo "<span class=\"versalita\">".$fila[1]."</span>";
  				}
  				//any
  				if ($fila[2]!="") {
  					echo " (".$fila[2]."). ";
  				}
  				//titol
  				echo "<i>".$fila[3]."</i>. ";
  				//edicio
  				if ($fila[4]!="") {
  					echo " (".$fila[4]."). ";
  				}
  				//editors literaris
  				if ($fila[5]!="") {
  					echo $fila[5].". ";
  				}
  				//lloc
  				if ($fila[6]!="") {
  					echo $fila[6].": ";
  				}
  				//editorial
  				echo $fila[7]."</p>\n";
			}
			if ($buit) {
				echo "<p class=\"advertencia\">No disposam de cap títol amb aquests criteris de cerca</p>\n";
			}
			echo "</div>\n";
			mysqli_free_result($result);
  		}
  		else {
  			echo "<p class=\"error\">Error 02: No es pot accedir al catàleg de bibilioteca</p>\n";
  			echo  $cadenaconsulta;
  		}
	}
	

  function inserir_data_sql($valor) {
    // substitueix el valor buit per nul sense cometes
    // per inserir correctament al comandament SQL
    if ($valor == "") {
      return "NULL";
    } else {
      return "'".$valor."'";
    }
  }


  function tornar($parametres) {
    // surt de la pàgina actual i torna a la pròpia
    // amb els paràmetres indicats
    header("Location:".$_SERVER['PHP_SELF'].$parametres);
  }


  ?>