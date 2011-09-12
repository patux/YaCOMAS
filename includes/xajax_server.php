<?php
if(!defined('TO_ROOT')) {
	define('TO_ROOT', '..');	
}
include TO_ROOT ."/vendors/xajax/xajax_core/xajax.inc.php";
$xajax= new xajax();

function buscarAsistente($apellido){
	$Response = new xajaxResponse();
	$DbConnection = DbConnection::getInstance();
	
	$apellido = mysql_real_escape_string($apellido);
	if ( strlen($apellido)>=3 ) {
		
		if ( $_GET['opc'] == 20  ) 
			$sql="SELECT id, concat(apellidos, ' ', nombrep) AS nombre, ciudad, mail
			FROM asistente
			WHERE apellidos LIKE '%$apellido%'";
		if ( $_GET['opc'] == 21 )
			$sql="SELECT a.id, concat(a.apellidos, ' ', a.nombrep) AS nombre, a.ciudad, a.mail
			FROM asistente AS a, pago AS p
			WHERE apellidos LIKE '%$apellido%' 
			AND p.id_responsable=a.id";
		if ( $_GET['opc'] == 22 )
      $sql="SELECT a.id, concat(a.apellidos, ' ', a.nombrep) AS nombre, a.ciudad, a.mail
      FROM asistente AS a, pago AS p
      WHERE apellidos LIKE '%$apellido%' 
      AND p.id_responsable=a.id";
		  
		$asistentes = $DbConnection->getAll($sql);
		
		$string = '';
		if( is_array($asistentes)) {
			$string .= "<table><tr><th></th><th>Nombre</th><th>Ciudad</th><th>Correo</th></tr>\n";
			foreach($asistentes AS $asistente) {
				extract($asistente);
				$string.= "<tr><td><a href=\"admin.php?opc=";
				$string.=$_GET['opc']; 
				$string.="&amp;tab=1&amp;id=$id\">Seleccionar</a></td>
				<td>$nombre</td><td>$ciudad</td><td>$mail</td>
				</tr>\n";
			}
			$string .="</table>";
			$Response->assign('results_table', 'innerHTML', $string);
		} else {
			$Response->assign('results_table', 'innerHTML', '<strong>No hay ningún resultado para su búsqueda.</strong>');
		}
	} else {
		$Response->assign('results_table', 'innerHTML', '<strong>Los resultados aparecerán a partir del 3er caracter que teclees.</strong>');
	}
	
	return $Response;
}

function buscarAsistentePorPago($apellido, $id_pago){
  $Response = new xajaxResponse();
  $DbConnection = DbConnection::getInstance();
  
  $apellido = mysql_real_escape_string($apellido);
  if ( strlen($apellido)>=3 ) {
    
    $sql="SELECT id, concat(apellidos, ' ', nombrep) AS nombre, ciudad, mail
        FROM asistente
        WHERE apellidos LIKE '%$apellido%'
        AND id_pago=0";
    $asistentes = $DbConnection->getAll($sql);
    
    $string = '';
    if( is_array($asistentes)) {
      $string .= "<table><tr><th></th><th>Nombre</th><th>Ciudad</th><th>Correo</th></tr>\n";
      foreach($asistentes AS $asistente) {
        extract($asistente);
        $string.= "<tr><td>";
        $string.="<input type=\"button\" onclick=\"xajax_anadirAsistente($id, $id_pago)\" value=\"Añadir\"/> ";
        $string .= "</td><td>$nombre</td><td>$ciudad</td><td>$mail</td>
        </tr>\n";
      }
      $string .="</table>";
      $Response->assign('results_table', 'innerHTML', $string);
    } else {
      $Response->assign('results_table', 'innerHTML', '<strong>No hay ningún resultado para su búsqueda.</strong>');
    }
  } else {
    $Response->assign('results_table', 'innerHTML', '<strong>Los resultados aparecerán a partir del 3er caracter que teclees.</strong>');
  }
  
  return $Response;
}

function guardarContinuar($data){
	$Response = new xajaxResponse();
	$edad = $data['edad']; unset($data['edad']);
	
	$id=(int)$data['id'];
	$Asistente = new AsistenteModel($id);
	if($id!=0) {
		$Asistente->load();
	} else {
		$Asistente->login = $data['mail'];
		$Asistente->passwd = md5($data['mail']);
	}
	
	if ( $edad!=0 ) {
		$Asistente->fecha_nac = (date('Y')-$edad).'-00-00';
	}
	
	foreach($data AS $field=>$value) {
		$Asistente->$field=$value;	
	}
	
	if ( !$Asistente->save() ) {
		$Response->alert('No se pudo guardar el asistente');
		return $Response;
	}
	$Response->redirect('admin.php?opc=20&tab=2&id=' . $Asistente->getId() );
	return $Response;
}

function editarCuota($id, $id_pago) {
	$Response = new xajaxResponse();
	
	$Cuota = new InscripcionModel((int)$id);
	if ( $id!=0 ){
		$Cuota->load();
	}
	$Cuota->id_pago=$id_pago;
	$CuotaForm = new Decorator($Cuota,'cuotaform');
	
	$content = $CuotaForm->getString();
	if ( $id==0 ){
		$Response->create('cuotas', 'li', 'cuota_0');
		$Response->assign('cuota_0', 'innerHTML', $content);
	} else {
		$Response->assign("cuota_$id", 'innerHTML', $content);
	}
	return $Response;
}
function cancelarEditarCuota($id) {
	$Response = new xajaxResponse();
	if ( $id==0 ){
		$Response->remove('cuota_0');
		return $Response;
	}
	$Cuota = new InscripcionModel((int)$id);
	$Cuota->load();
	$CuotaLi = new Decorator($Cuota,'cuotali');
	$content = $CuotaLi->getString();
	$Response->assign("cuota_$id", 'innerHTML', $content);
	return $Response;
}
function eliminarCuota($id) {
	$Response = new xajaxResponse();
	
	$Cuota = new InscripcionModel((int)$id);
	$Cuota->delete();
	$Response->remove("cuota_$id");
	$Response->script("xajax_actualizarTotales(xajax.$('id_pago').value);");
	return $Response;
}

function guardarCuota($data){
	$Response = new xajaxResponse();
	$id = $data['id'] = $data['id_inscripcionp'];
	unset($data['id_inscripcionp']);
	$Cuota = new InscripcionModel((int)$id);
	foreach ($data as $field => $value ) {
		 $Cuota->$field=$value;
	}
	if( !$Cuota->save() ) {
		$DbConnection = DbConnection::getInstance();
		$Response->alert($DbConnection->getLastError());
		return $Response;
	}
	$Cuota->load();
	if($id==0) {
		$Response->remove('cuota_0');
		$Response->create('cuotas', 'li', 'cuota_'.$Cuota->getId());
	}
	$CuotaLi = new Decorator($Cuota,'cuotali');
	$content = $CuotaLi->getString();
	$Response->assign("cuota_".$Cuota->getId(), 'innerHTML', $content);
	$Response->script("xajax_actualizarTotales(xajax.$('id_pago').value);");
	return $Response;
}

function editarHospedaje($id, $id_pago) {
	$Response = new xajaxResponse();
	
	$Hospedaje = new HospedajeModel((int)$id);
	if ( $id!=0 ){
		$Hospedaje->load();
	}
	$Hospedaje->id_pago=$id_pago;
	$HospedajeForm = new Decorator($Hospedaje,'hospedajeform');
	
	$content = $HospedajeForm->getString();
	if ( $id==0 ){
		$Response->create('hospedajes', 'li', 'hospedaje_0');
		$Response->assign('hospedaje_0', 'innerHTML', $content);
	} else {
		$Response->assign("hospedaje_$id", 'innerHTML', $content);
	}
	return $Response;
}
function cancelarEditarHospedaje($id) {
	$Response = new xajaxResponse();
	if ( $id==0 ){
		$Response->remove('hospedaje_0');
		return $Response;
	}
	$Hospedaje = new HospedajeModel((int)$id);
	$Hospedaje->load();
	$HospedajeLi = new Decorator($Hospedaje,'hospedajeli');
	$content = $HospedajeLi->getString();
	$Response->assign("hospedaje_$id", 'innerHTML', $content);
	return $Response;
}
function eliminarHospedaje($id) {
	$Response = new xajaxResponse();
	
	$Hospedaje = new HospedajeModel((int)$id);
	$Hospedaje->delete();
	$Response->remove("hospedaje_$id");
	$Response->script("xajax_actualizarTotales(xajax.$('id_pago').value);");
	return $Response;
}

function guardarHospedaje($data){
	$Response = new xajaxResponse();
	$id = $data['id'] = $data['id_hospedajep'];
	unset($data['id_hospedajep']);
	$Hospedaje = new HospedajeModel((int)$id);
	foreach ($data as $field => $value ) {
		 $Hospedaje->$field=$value;
	}
	if( !$Hospedaje->save() ) {
		$DbConnection = DbConnection::getInstance();
		$Response->alert($DbConnection->getLastError());
		return $Response;
	}
	$Hospedaje->load();
	if($id==0) {
		$Response->remove('hospedaje_0');
		$Response->create('hospedajes', 'li', 'hospedaje_'.$Hospedaje->getId());
	}
	$HospedajeLi = new Decorator($Hospedaje,'hospedajeli');
	$content = $HospedajeLi->getString();
	$Response->assign("hospedaje_".$Hospedaje->getId(), 'innerHTML', $content);
	$Response->script("xajax_actualizarTotales(xajax.$('id_pago').value);");
	return $Response;
}

function guardarDescuento($id_pago, $porc_descuento){
	$Response = new xajaxResponse();
	$Pago = new PagoModel((int)$id_pago);
	$Pago->load();
	$Pago->porc_descuento=$porc_descuento;
	$Pago->save();
	
	$Response->script("xajax_actualizarTotales(xajax.$('id_pago').value);");
	return $Response;
}

function actualizarTotales($id_pago){
	$Response = new xajaxResponse();
	$Pago = new PagoModel((int)$id_pago);
	$Pago->load();
	
	$total_cuotas = $Pago->getTotalCuotas();
	$subtotal_cuotas = $Pago->getTotalCuotas(false);
	$total_hospedajes = $Pago->getTotalHospedajes();
	$total = $Pago->getTotal();
	
	$Response->assign('subtotal_cuotas', 'innerHTML', "Sub Total Inscripciones: \$ $subtotal_cuotas");	
	$Response->assign('total_descuento', 'innerHTML', "Total Descuento: \$ " . ceil($subtotal_cuotas - $total_cuotas));
	$Response->assign('total_cuotas', 'innerHTML', "Total Inscripciones: \$ $total_cuotas");
	$Response->assign('total_hospedajes', 'innerHTML', "Total Hospedajes: \$ $total_hospedajes");
	$Response->assign('total', 'innerHTML', "Total: \$ $total");
	
	return $Response;
}

function guardarPago($data){
	$Response = new xajaxResponse();
	$id = $data['id'] = $data['id_pago'];
	unset($data['id_pago']);
	$Pago = new PagoModel((int)$id);
	$Pago->load();
	foreach ($data as $field => $value ) {
		 $Pago->$field=$value;
	}
	$Pago->monto_neto  = $Pago->getTotal(false);
	$Pago->monto_cuotas = $Pago->getTotalCuotas(false);
	$Pago->monto_hospedaje = $Pago->getTotalHospedajes(false);
	if( !$Pago->save() ) {
		$DbConnection = DbConnection::getInstance();
		$Response->alert($DbConnection->getLastError());
		return $Response;
	}
	return $Response;
}

function editarFactura($id_pago) {
	$Response = new xajaxResponse();
	$Pago = new PagoModel((int)$id_pago);
	$Pago->load();
	
	if( !$Factura = $Pago->getFactura() ) {
		$Factura = new FacturaModel(0);
	}
	$FacturaForm = new Decorator($Factura,'facturaform');
	$FacturaForm->assign('id_pago', $id_pago);
	$content = $FacturaForm->getString();
	$Response->assign("factura_container", 'innerHTML', $content);
	return $Response;
}

function guardarFactura($data, $id_pago){
	$Response = new xajaxResponse();
	$id = $data['id'] = $data['id_factura'];
	unset($data['id_factura']);
	
	
	$Factura = new FacturaModel((int)$id);
	$Factura->load();
	foreach ($data as $field => $value ) {
		 $Factura->$field=$value;
	}
	if( !$Factura->save() ) {
		$DbConnection = DbConnection::getInstance();
		$Response->alert($DbConnection->getLastError());
		return $Response;
	}
	$Pago = new PagoModel((int)$id_pago);
	$Pago->id_factura = $Factura->getId();
	$Pago->save();
	
	$FacturaDetail = new Decorator($Factura, 'facturadetail');
	$FacturaDetail->assign('id_pago', $id_pago);
	
	$Response->assign('factura_container', 'innerHTML', $FacturaDetail->getString());
	return $Response;
}

function eliminarFactura($id_factura){
	$Response = new xajaxResponse();
	$DbConnection = DbConnection::getInstance();
		
	$Factura = new FacturaModel((int)$id_factura);
	$Factura->delete();
	
	$sql = "SELECT id FROM pago WHERE id_factura='$id_factura'";
	$id_pago = $DbConnection->getOne($sql);
	$Pago = new PagoModel((int)$id_pago);
	$Pago->id_factura = 0;
	$Pago->save();
	
	$Response->assign('factura_container', 'innerHTML', '<input type="button" onclick="xajax_editarFactura('.$id_pago.')" value="Crear Factura"/>');
	return $Response;
}

function cancelarEditarFactura($id_pago){
  $Response = new xajaxResponse();
  $DbConnection = DbConnection::getInstance();

  $Pago = new PagoModel((int)$id_pago);
  $Pago->load();
  if ( !$Factura=$Pago->getFactura()) {
    $Response->assign('factura_container', 'innerHTML', '<input type="button" onclick="xajax_editarFactura('.$id_pago.')" value="Crear Factura"/>');
    return $Response;
  }
  $FacturaDetail = new Decorator($Factura,'facturadetail');
  $FacturaDetail->assign('id_pago', $id_pago);
  $Response->assign('factura_container', 'innerHTML', $FacturaDetail->getString());
  return $Response;
}

function abrirEliminarPago($id_pago){
  $Response = new xajaxResponse();
  $Response->confirmCommands(1, '¿Realmente Desea Eliminar el Pago?');
  $Response->script('xajax_realmenteEliminarPago('.$id_pago.')');
  return $Response;
}

function realmenteEliminarPago($id_pago) {
  $Response = new xajaxResponse();
  $Pago = new PagoModel((int)$id_pago);
  $Pago->load();
  $Pago->delete();
  
  $Response->alert('El pago ha sido eliminado');
  $Response->redirect('admin.php?opc=20');
  return $Response;
}

function abrirConfirmarPago($id_pago){
  $Response = new xajaxResponse();
  $Response->confirmCommands(1, '¿Confirmar el Pago?');
  $Response->script('xajax_realmenteConfirmarPago('.$id_pago.')');
  return $Response;
}

function realmenteConfirmarPago($id_pago) {
  $Response = new xajaxResponse();
  $Pago = new PagoModel((int)$id_pago);
  $Pago->pagado = 1;
  $Pago->save();
  
  $Response->redirect('admin.php?opc=21');
  return $Response;
}

function eliminarAsistente($id_asistente) {
  $Response = new xajaxResponse();
  $Asistente = new AsistenteModel((int)$id_asistente);
  $Asistente->load();
  
  $Pago = new PagoModel((int)$Asistente->id_pago);
  $Pago->load();
  
  if($id_asistente==$Pago->id_responsable) {
    $Response->alert('No puedes eliminar a ese usuario del grupo, es el responsable');
    return $Response;
  }
  $Asistente->id_pago = 0;
  $Asistente->save();
  
  $AsistentesLi = new Decorator($Pago, 'asistentesli');
  $Response->assign('asistentes_list', 'innerHTML',$AsistentesLi->getString());
  
  return $Response;
}

function abrirDialogoAnadirAsistente($id_pago) {
  $Response = new xajaxResponse();
  $Pago= new PagoModel((int)$id_pago);
  $Pago->load();
  $BusquedaAsistentes = new Decorator($Pago, 'busquedaasistentes');
  $Response->script('showModal()');
  $Response->assign('modal_dialog', 'innerHTML', $BusquedaAsistentes->getString() );
  $Response->script("xajax.$('apellido').focus()");
  return $Response;
}

function anadirAsistente($id_asistente, $id_pago) {
  $Response = new xajaxResponse();
  
  $Asistente = new AsistenteModel((int)$id_asistente);
  $Asistente->id_pago = $id_pago;
  $Asistente->save();
  
  $Pago = new PagoModel((int)$id_pago);
  $Pago->load();
  
  $AsistentesLi = new Decorator($Pago, 'asistentesli');
  $Response->assign('asistentes_list', 'innerHTML', $AsistentesLi->getString());
  
  $Response->script("xajax_buscarAsistentePorPago(xajax.$('apellido').value, $id_pago)"); 
  
  return $Response;  
}

function abrirDialogoNuevoAsistente($id_pago) {
  $Response = new xajaxResponse();
  $Asistente = new AsistenteModel(0);
  $Asistente->id_pago = $id_pago;
  
  $DbConnection = DbConnection::getInstance();
  
  $sql="SELECT id, descr FROM estudios;";
  $estudios=$DbConnection->getAssoc($sql);
  
  $sql="SELECT id, descr FROM estado;";
  $estados=$DbConnection->getAssoc($sql);
  
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
  
  $AsistenteForm = new Decorator($Asistente, 'asistenteform');
  
  $AsistenteForm ->assign('estudios', $estudios);
  $AsistenteForm ->assign('edades', $edades);
  $AsistenteForm ->assign('estados', $estados);
  
  $Response->script('showModal()');
  $Response->assign('modal_dialog', 'innerHTML', $AsistenteForm->getString() );
  $Response->script("xajax.$('nombrep').focus()");
  
  return $Response;
}

function guardarAsistente($data){
  $Response = new xajaxResponse();
  $edad = $data['edad']; unset($data['edad']);
  
  $id=(int)$data['id'];
  $Asistente = new AsistenteModel($id);
  if($id!=0) {
    $Asistente->load();
  } else {
    $Asistente->login = $data['mail'];
    $Asistente->passwd = md5($data['mail']);
  }
  
  if ( $edad!=0 ) {
    $Asistente->fecha_nac = (date('Y')-$edad).'-00-00';
  }
  
  foreach($data AS $field=>$value) {
    $Asistente->$field=$value;  
  }
  
  if ( !$Asistente->save() ) {
    $Response->alert('No se pudo guardar el asistente');
    return $Response;
  }
  
  $Pago = new PagoModel((int)$data['id_pago']);
  $Pago->load();
  
  $AsistentesLi = new Decorator($Pago, 'asistentesli');
  $Response->assign('asistentes_list', 'innerHTML', $AsistentesLi->getString());
  $Response->script('hideModal();');
  return $Response;
}

$xajax->registerFunction('buscarAsistente');
$xajax->registerFunction('guardarContinuar');

$xajax->registerFunction('editarCuota');
$xajax->registerFunction('guardarCuota');
$xajax->registerFunction('eliminarCuota');
$xajax->registerFunction('cancelarEditarCuota');

$xajax->registerFunction('editarHospedaje');
$xajax->registerFunction('guardarHospedaje');
$xajax->registerFunction('eliminarHospedaje');
$xajax->registerFunction('cancelarEditarHospedaje');

$xajax->registerFunction('guardarDescuento');
$xajax->registerFunction('actualizarTotales');
$xajax->registerFunction('guardarPago');

$xajax->registerFunction('editarFactura');
$xajax->registerFunction('cancelarEditarFactura');
$xajax->registerFunction('guardarFactura');
$xajax->registerFunction('eliminarFactura');

$xajax->registerFunction('abrirEliminarPago');
$xajax->registerFunction('realmenteEliminarPago');

$xajax->registerFunction('abrirConfirmarPago');
$xajax->registerFunction('realmenteConfirmarPago');

$xajax->registerFunction('abrirDialogoAnadirAsistente');
$xajax->registerFunction('abrirDialogoNuevoAsistente');
$xajax->registerFunction('buscarAsistentePorPago');
$xajax->registerFunction('anadirAsistente');
$xajax->registerFunction('guardarAsistente');
$xajax->registerFunction('eliminarAsistente');

$xajax->configure('javascript URI', TO_ROOT . "/vendors/xajax/");
$xajax->configure('characterEncoding', 'iso-8859-1');
$xajax->processRequest();
