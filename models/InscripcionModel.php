<?php
class InscripcionModel extends DAO{
	public function __construct($id) {
		parent::__construct('inscripcionp', $id);
	}
	public static function getTipos() {
		$DbConnection = DbConnection::getInstance();
		$sql="select id, descr, costo FROM tcuota";
		return $DbConnection->getAll($sql);
	}
	public function getDescr() {
		$this->assertLoaded();
		$sql="select descr FROM tcuota WHERE id='$this->id_tcuota'";
		return $this->_DbConnection->getOne($sql);
	}
	public function getCosto() {
		$this->assertLoaded();
		$sql="select costo FROM tcuota WHERE id='$this->id_tcuota'";
		return $this->_DbConnection->getOne($sql);
	}
	public function getTotal(){
		$this->assertLoaded();
		return $this->_data['no_personas'] * $this->getCosto();
	}
}