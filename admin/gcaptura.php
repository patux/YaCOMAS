<?php
$id =(empty($_GET['id']))?0:(int)$_GET['id'];
if ($id == 0) {
  header('location: admin.php?opc=22');
}

$Responsable = new AsistenteModel($id);
$Responsable->load();
$Pago = $Responsable->getPago();
$asistentes = $Pago->getAsistentes();

$View->assign('Pago', $Pago);
$View->assign('Responsable', $Responsable);
$View->assign('asistentes', $asistentes);
