<?
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
$tok = strtok ($_GET['basistente']," ");
$idasistente=$tok;
$tok = strtok (" ");
$letra=$tok;
$Query_actualiza= "DELETE FROM asistente WHERE id="."'".$idasistente."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo eliminar el asistente".mysql_errno($actualiza_registro));
	header('Location: '.$fslpath.$rootpath.'/admin/RAsistencia.php?letra='.$letra);
?>
