<?
		switch ($_GET['opc']) 
		{
			case 1: include "Masistente.php";
				break;
			case 2: include "Leventos.php";
				break;
			case 3: include "Ltalleres.php";
				break;
			case 4: include "Ltalleres-reg.php";
				break;
			case 5: include "encuesta.php";
				break;
			case 6: include "HojaRegistro.php";
				break;
		}	
?>
