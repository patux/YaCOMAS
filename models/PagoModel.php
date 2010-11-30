<?php
class PagoModel extends DAO{
	/**
	 * @var FacturaModel;
	 */
	protected $_Factura = null;
	
	public function __construct($id) {
		parent::__construct('pago', $id);
	}
	
	/**
	 * @return FacturaModel
	 */
	public function getFactura(){
		$this->assertLoaded();
		if ( !isset($this->_Factura) ) {
			if( $this->_data['id_factura']==0 ) {
				return false;	
			}
			$this->_Factura = new FacturaModel((int)$this->_data['id_factura']);
			$this->_Factura->load();
		}
		return $this->_Factura;
	}
	
	public function getCuotas() {
		if($this->_id ==0 ) {
			return array();
		}
		$this->assertLoaded();
		$sql = "SELECT id FROM inscripcionp WHERE id_pago='{$this->_id}'";
		$cuotas = array();
		if( $ids = $this->_DbConnection->getColumn($sql)) {
			foreach ($ids AS $id) {
				$Cuota = new InscripcionModel((int)$id);
				$Cuota->load();
				$cuotas[]=$Cuota;
			}
		}
		return $cuotas;
	}
	
	public function getAsistentes() {
		if($this->_id == 0 ) {
			return array();
		}
		$this->assertLoaded();
		$sql = "SELECT id FROM asistente WHERE id_pago='{$this->_id}'";
		$asistentes = array();
		if( $ids = $this->_DbConnection->getColumn($sql)) {
			foreach ($ids AS $id) {
				$Asistente = new AsistenteModel((int)$id);
				$Asistente->load();
				$asistentes[]=$Asistente;
			}
		}
		return $asistentes;
	}
	
	public function getHospedajes() {
		if($this->_id ==0 ) {
			return array();
		}
		$this->assertLoaded();
		$sql = "SELECT id FROM hospedajep WHERE id_pago='{$this->_id}'";
		$hospedajes = array();
		if( $ids = $this->_DbConnection->getColumn($sql)) {
			foreach ($ids AS $id) {
				$Hospedaje = new HospedajeModel((int)$id);
				$Hospedaje->load();
				$hospedajes[]=$Hospedaje;
			}
		}
		return $hospedajes;
	}
	
	public function getTotalHospedajes() {
		$this->assertLoaded();
		$hospedajes = $this->getHospedajes();
		$total = 0;
		foreach($hospedajes AS $Hospedaje) {
			$total += $Hospedaje->getTotal();
		}
		return ceil($total);
	}
	
	public function getTotalCuotas($descontado = true) {
		$this->assertLoaded();
		$cuotas = $this->getCuotas();
		$total = 0;
		foreach($cuotas AS $Cuotas) {
			$total += $Cuotas->getTotal();
		}
		if ( $descontado ) {
			return $total-($total * ($this->_data['porc_descuento']/100));
		}
		return ceil($total);
	}
	
	public function getTotal($descontado = true) {
		$this->assertLoaded();
		return $this->getTotalCuotas($descontado) + $this->getTotalHospedajes();
	}
	
	public function delete(){
     $hospedajes = $this->getHospedajes();
	  foreach($hospedajes AS $Hospedaje) {
	    $Hospedaje->delete();
	  }
	  $asistentes = $this->getAsistentes();
	  foreach($asistentes AS $Asistente) {
	    $Asistente->id_pago = 0;
	    $Asistente->save();
	  }
	  $cuotas = $this->getCuotas();
    foreach($cuotas AS $Cuota) {
      $Cuota->delete();
    }
    if($Factura=$this->getFactura()) {
      $Factura->delete();
    }
    parent::delete();  
	}
}