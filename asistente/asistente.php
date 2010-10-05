<?php 
include_once "../includes/conf.inc.php";
		switch ($_GET['opc']) 
		{
			case MASISTENTE: include "Masistente.php";
				break;
			case LEVENTOS: include "planner.php";
				break;
			case LTALLERES: include "Ltalleres.php";
				break;
			case LTALLERESREG: include "Ltalleres-reg.php";
				break;
			case ENCUESTA: include "encuesta.php";
				break;
			case HOJAREGISTRO: include "HojaRegistro.php";
				break;
		}	
?>
