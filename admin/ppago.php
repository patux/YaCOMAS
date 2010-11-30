<?php
$DbConnection = DbConnection::getInstance();

$id_asistente =(int)$_GET['id'];
if($id_asistente == 0) {
	header('location: admin.php?opc=20');
}

$Asistente = new AsistenteModel($id_asistente);
$Asistente->load();

$Pago = $Asistente->getPago();
if ($Pago === false){
	$Pago = new PagoModel(0);
	$Pago->id_responsable = $Asistente->getId();
	$Pago->fecha_pago = date("Y-m-d H:i:s");
	$Pago->save();$Pago->load();
	$Asistente->id_pago = $Pago->getId();
	$Asistente->save();
	$Factura = new FacturaModel(0);
} else {
	$Factura = $Pago->getFactura();
	if($Factura === false) {
		$Factura = new FacturaModel(0);
	}
}	

$View->assign('Asistente', $Asistente);
$View->assign('Pago', $Pago);
$View->assign('Factura', $Factura);