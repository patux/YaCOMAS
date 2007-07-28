<?
		if (isset($_GET['opc']))
			switch ($_GET['opc']) 
			{
				case 1:include "Nasistente.php";
					break;
			}	
		else
			include "signin.php";
?>
