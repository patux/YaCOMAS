<?
include_once "../includes/conf.inc.php";
		if (isset($_GET['opc']))
			switch ($_GET['opc']) 
			{
				case NASISTENTE:include "Nasistente.php";
					break;
			}	
		else
			include "signin.php";
?>
