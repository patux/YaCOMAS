<?php
class AsistenteModel extends DAO{
	/**
	 * @var PagoModel
	 */
	protected $_Pago = null;
	
	public function __construct($id) {
		parent::__construct('asistente', $id);
	}
	/**
	 * @return PagoModel
	 */
	public function getPago(){
		$this->assertLoaded();
		if ( !isset($this->_Pago) ) {
			$sql = "SELECT id FROM pago
					WHERE id_responsable='{$this->_id}';";
			if( ($id_pago = $this->_DbConnection->getOne($sql))==false ) {
				return false;	
			}
			$this->_Pago = new PagoModel((int)$id_pago);
			$this->_Pago->load();
		}
		return $this->_Pago;
	}
}