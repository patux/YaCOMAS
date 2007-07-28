<?
include_once "../includes/conf.inc.php";
		switch ($_GET['opc']) 
		{
			case NPONENCIA:include "Nponencia.php";
				break;
			case PROPUESTAENV:include "Lponencias.php";
				break;
			case MPONENTE: include "Mponente.php";
				break;
		}	
?>
