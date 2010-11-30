<?php
$DbConnection = DbConnection::getInstance();

$id_asistente =(int)$_GET['id'];
if($id_asistente == 0) {
	$opc=$_GET['opc'];
	header('location: admin.php?opc='.$opc);
}

$Asistente = new AsistenteModel($id_asistente);
$Asistente->load();

$Pago = $Asistente->getPago();
if ($Pago === false){
	$Pago = new PagoModel(0);
	$Pago->id_responsable = $Asistente->getId();
	$Pago->save();$Pago->load();
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