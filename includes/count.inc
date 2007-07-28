<?php
// En: Begin PHP Code / Fr: Debut code PHP
/******************************************************************************\
* Text Counter                                 Version 1.0                     *
* Copyright 2000 Frederic TYNDIUK (FTLS)       All Rights Reserved.            *
* E-Mail: tyndiuk@ftls.org                     Script License: GPL             *
* Created  02/28/2000                          Last Modified 02/28/2000        *
* Scripts Archive at:                          http://www.ftls.org/php/        *
*******************************************************************************/
// Necessary Variables:

$COUNT_FILE = "count_data.txt";
	// En: Absolute path and name to count data file.
	// Fr: Chemin absolu (complet) et Nom du fichier compteur.

// End  Necessary Variables section
/******************************************************************************/

if (file_exists($COUNT_FILE)) {
	// En: Open, read, increment, save and close file.
	// Fr: Ouvre, lit, incrémente, sauve et ferme le fichier.
	$fp = fopen("$COUNT_FILE", "r+");
	flock($fp, 1);
	$count = fgets($fp, 4096);
	$count += 1; 
	fseek($fp,0);
	fputs($fp, $count);
	flock($fp, 3);
	fclose($fp);
} else {
	// En: Display a error message if file does not exist.
	// Fr: Affiche un message d'erreur si le fichier n'existe pas.
	echo "Can't find file, check '\$file' var...<BR>";
}

// En: End PHP Code
// Fr: Fin code PHP
?>
