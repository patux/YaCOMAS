<?
		switch ($_GET['opc']) 
		{
			case 'C': include "config.php";
				break;
			case 0: include "Nadmin.php";
				break;
			case 1: include "Ladmin.php";
				break;
			case 2: include "LBponencias.php";
				break;
			case 3: include "Madmin.php";
				break;
			case 4: include "Nlugar.php";
				break;
			case 5: include "Llugar.php";
				break;
			case 6: include "Lponentes.php";
				break;
			case 7: include "Lponencias.php";
				break;
			case 8: include "Aevento.php";
				break;
			case 9: include "Leventos.php";
				break;
			case 11: include "Nfecha.php";
				break;
			case 12: include "Lfecha.php";
				break;
			case 13: include "Lasistentes.php";
				break;
			case 15: include "RAsistencia.php";
				break;
			case 16: include "Nponente.php";
				break;
			case 17: include "Nponencia.php";
				break;
			case 18: include "RAsistente.php";
				break;
			case 19: include "BajaAsistenteTaller.php";
				break;
			default: include "signin.php";
				break;
		}	
?>
