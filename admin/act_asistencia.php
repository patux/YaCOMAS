<?
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
$tok = strtok ($_GET['casistencia']," ");
$idasistente=$tok;
$tok = strtok (" ");
$asistencia=$tok;
$tok = strtok (" ");
$letra=$tok;
if ($asistencia!=0)
	$asistencia=0;
else
	$asistencia=1;
$Query_actualiza= "UPDATE asistente SET asistencia="."'".$asistencia."'
			   WHERE id="."'".$idasistente."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la configuracion ".mysql_errno($actualiza_registro));
	header('Location: '.$fslpath.$rootpath.'/admin/RAsistencia.php?letra='.$letra);
?>
