<?
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
$tok = strtok ($_GET['vconf']," ");
$idconf=$tok;
$tok = strtok (" ");
$idstatus=$tok;
$Query_actualiza= "UPDATE config SET status="."'".$idstatus."'
			   WHERE id="."'".$idconf."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la configuracion ".mysql_errno($actualiza_registro));

	header('Location: '.$fslpath.$rootpath.'/admin/admin.php?opc=C');
?>
