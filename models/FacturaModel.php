<?php
class FacturaModel extends DAO{
	public function __construct($id) {
		parent::__construct('factura', $id);
	}
}