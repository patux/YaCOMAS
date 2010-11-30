<?php
class EventModel extends DAO{
	public function __construct($id) {
		parent::__construct('evento', $id);
	}
}