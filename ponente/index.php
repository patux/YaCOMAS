<?
include_once "../includes/conf.inc.php";
		if (isset($_GET['opc']))
			switch ($_GET['opc']) 
			{
				case NPONENTE:include "Nponente.php";
					break;
			}		
		else
			include "signin.php";
?>
