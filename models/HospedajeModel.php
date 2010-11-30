<?php
class HospedajeModel extends DAO{
	public function __construct($id) {
		parent::__construct('hospedajep', $id);
	}
	public static function getTipos() {
		$DbConnection = DbConnection::getInstance();
		$sql="select id, descr, costo FROM thospedaje";
		return $DbConnection->getAll($sql);
	}
	public function getDescr() {
		$this->assertLoaded();
		$sql="select descr FROM thospedaje WHERE id='$this->id_thospedaje'";
		return $this->_DbConnection->getOne($sql);
	}
	public function getCosto() {
		$this->assertLoaded();
		$sql="select costo FROM thospedaje WHERE id='$this->id_thospedaje'";
		return $this->_DbConnection->getOne($sql);
	}
	public function getTotal(){
		$this->assertLoaded();
		return $this->_data['no_personas'] * $this->_data['no_noches'] * $this->getCosto();
	}
}