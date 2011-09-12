<?php
$id =(empty($_GET['id']))?0:(int)$_GET['id'];
$View->assign('id', $id);
$Asistente = new AsistenteModel($id);

$data=array();
if ($id!=0) {
	$Asistente->load();
	$data = $Asistente->getAllData();
}
$View->assign('data', $data);


$DbConnection = DbConnection::getInstance();
$sql="SELECT id, descr FROM estudios;";
$estudios=$DbConnection->getAssoc($sql);
$View->assign('estudios', $estudios);

$sql="SELECT id, descr FROM estado;";
$estados=$DbConnection->getAssoc($sql);
$View->assign('estados', $estados);

$edades=array(
	10 => 'Menor de 15',
	16 => '15 a 18',
	22 => '19 a 24',
	28 => '25 a 30',
	33 => '31 a 35',
	38 => '36 a 40',
	43 => '41 a 45',
	48 => '46 a 50',
	53 => '51 a 55',
	60 => 'Mas de 55',
	0  => 'No aplica',
);
if($id==0 || $data['fecha_nac']=="0000-00-00" || is_null($data['fecha_nac'])){
	$View->assign('edad', '22');	
}
$View->assign('edades', $edades);
