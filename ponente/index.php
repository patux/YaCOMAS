<?
		if (isset($_GET['opc']))
			switch ($_GET['opc']) 
			{
				case 1:include "Nponente.php";
					break;
			}		
		else
			include "signin.php";
?>
