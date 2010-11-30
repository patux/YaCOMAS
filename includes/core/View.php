<?php
/**
 * @package Garson
 * @author Argel Arias <levhita@gmail.com>
 */

/**
 * {@link View} Class, all custom classes are made from this one
 */
class View {
  /**
   * Holds variables to be passed to the templates
   * @var Array
   */
  protected $_variables = array();
  
  /**
   * Fullpath of the template to be shown.
   * @var string
   */
  protected $_template = '';
  
  /**
   * Fullpath of the layout to be shown
   * @var unknown_type
   */
  protected $_layout = '';
  
  /**
   * Constructor for class {@link View}
   * @param string $template_name
   * @return View
   */
  public function __construct($templateName, $layoutName = 'default')
  {
    $this->setTemplate($templateName);
    $this->setLayout($layoutName);
  }
  
  /**
   * Sets the template to be shown
   * @param string $template_name the template name, it'll be completed with a
   * path and extension.
   * @throws InvalidArgumentException
   * @return null
   */
  public function setTemplate($templateName)
  {
    $template = TO_ROOT . "/views/$templateName.phtml";
    if ( !file_exists($template) ) {
      throw new InvalidArgumentException("Couldn't find template '$template'");
    }
    $this->_template = $template;
  }
  
  /**
   * Sets the template to be shown
   * @param string $template_name the template name, it'll be completed with a
   * path and extension.
   * @throws InvalidArgumentException
   * @return null
   */
  public function setLayout($layoutName)
  {
    $layout = TO_ROOT . "/layouts/$layoutName.phtml";
    if ( !file_exists($layout) ) {
      throw new InvalidArgumentException("Couldn't find layout '$layout'");
    }
    $this->_layout = $layout;
  }
  
  /**
   * Assigns a variable to be visible in the Template
   * @param string $field
   * @param var $value
   * @return null
   */
  public function assign( $field, $value ) {
    $this->_variables[ $field ] = $value;
  }
  
  /**
   * Displays the Template
   * @return null
   */
  public function display() {
    
  	extract($this->_variables);
    
    ob_start();
    include $this->_template;
      $_template_content_ = ob_get_contents();
    ob_end_clean();
    
    include $this->_layout;
  }
}