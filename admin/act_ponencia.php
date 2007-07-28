<?
include "../includes/lib.php";
include "../includes/conf.inc";
beginSession('R');
$link=conectaBD();
	$tok = strtok ($_GET['vact']," ");
	$idponencia=$tok;
	$tok = strtok (" ");
	$idstatus=$tok;
	$tok = strtok (" ");
	$regresa=$tok;
	$Query_actualiza= "UPDATE propuesta SET id_status="."'".$idstatus."',
				  id_administrador="."'".$_SESSION['YACOMASVARS']['rootid']."'
			   WHERE id="."'".$idponencia."'";
	$actualiza_registro= mysql_query($Query_actualiza) or err("No se pudo actualizar la ponencia".mysql_errno($actualiza_registro));
	$regresar='Location: '.$regresa;

	header($regresar);
?>
