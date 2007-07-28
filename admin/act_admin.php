<?
include "../includes/lib.php";
include "../includes/conf.inc.php";
beginSession('R');
$link=conectaBD();
	$tok = strtok ($_GET['vact']," ");
	$idadmin=$tok;
	$tok = strtok (" ");
	$idtadmin=$tok;
	$tok = strtok (" ");
	$regresa=$tok;
	$Query_actualiza= "UPDATE administrador SET id_tadmin="."'".$idtadmin."'
			   WHERE id="."'".$idadmin."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la Administrador".mysql_errno($actualiza_registro));
	$regresar='Location: '.$regresa;

	header($regresar);
?>
