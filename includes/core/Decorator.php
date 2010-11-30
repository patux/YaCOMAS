<?php
class Decorator {
	/**
	 * The DAO to be decorated
	 * @var DAO
	 */
	protected $_DAO=null;
	
	/**
	 * The template to be used
	 * @var string
	 */
	protected $_template = '';
	
	/**
	 * @var array
	 */
	protected $_variables = array();
	
	/**
	 * @param $DAO
	 * @param $template_name
	 */
	public function __construct(DAO $DAO, $template_name='') {
		$template_name =(empty($template_name))?strtolower(get_class($DAO)):$template_name;
		$this->_DAO = $DAO;
		$this->_template = $template_name.".phtml";
	}

	public function assign($variable, $value){
		$this->_variables[$variable] = $value;
	}
	
	public function getString(){
		$DAO = $this->_DAO;
		extract($this->_variables);
		ob_start();
			include TO_ROOT ."/models/templates/$this->_template";
		$string = ob_get_contents();
		ob_end_clean();
		return $string; 
	}
}