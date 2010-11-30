<?php
/**
 * Holds {@link DbConnection} class
 * @package Garson
 * @author Argel Arias <levhita@gmail.com>
 * @copyright Copyright (c) 2007, Argel Arias <levhita@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
  * Database Connection abstraction
  *
  * Provides extremely useful functions for data retrieval, and other database
  * affairs.
  * @todo Change to use PDO-MySQL, Totally abstracted if posible
  * @package Garson
  */
class DbConnection {
  
  /**
   * Holds the instance of the singleton
   * @var DbConnection
   */
  protected static $__instance = null;
  /**
   * Holds the MySQL Link to the Database
   * @var resource
   */
  protected $_db_connection  = null;
  /**
   * An array with all the errors
   * @var array
   */
  protected $_errors = array();
  
  /**
   * Protected constructor
   * @return DbConnection
   */
  protected function __construct()
  {
	include TO_ROOT."/includes/db.inc.php";
  	
	if ( !$this->_db_connection = @mysql_connect($dbhost, $dbuser, $dbpwd) ) {
      throw new RunTimeException("Couldn't connect to the database server");
    }
    if ( !@mysql_select_db($dbname, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't connect to the given database");
    }
    mysql_query('SET CHARACTER SET iso-8859-1');
    
  }
  
  /**
   * Get a single instance of the class (Singleton)
   * @return DbConnection
   */
  public static function getInstance() {
    if ( !self::$__instance instanceof self ) {
      self::$__instance = new self;
    }
    
    return self::$__instance;
  }
  
  /**
   * Gets an Multidimensional associative array, with all the results:
   * Array(
   *  1=> array( id=1, nick=levhita, name=argel, lastname=arias)
   *  2=> array( id=32, nick=renich, name=renich, lastname=bon)
   *  3=> array( id=5, nick=b3t0, name=b3, lastname=t0)
   *  )
   * @param string $sql
   * @return array
   */
  public function getAll($sql)
  {
    if ( !$results = @mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    
    $count = 0;
    $rows  = array();
    while ( $row = mysql_fetch_assoc($results) ) {
      $rows[] = $row;
      $count++;
    }
    return ($count)?$rows:false;
  }
  
  /**
   * Gets one array with the results in one column:
   * Array (
   *   1=levhita
   *   2=renich
   *   3=b3t0
   * )
   * @param string $sql
   * @return unknown_type
   */
  public function getColumn($sql)
  {
    if ( !$results = @mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    
    $count = 0;
    $rows  = array();
    while ( $row = mysql_fetch_array($results) ) {
      $rows[] = $row[0];
      $count++;
    }
    return ($count)?$rows:false;
  }
  
  /**
   * Gets an array as a key => value pair:
   * Array (
   *   1=levhita
   *   32=renich
   *   5=b3t0
   * )
   * @param string $sql
   * @return array
   */
  public function getAssoc($sql)
  {
    if ( !$results = @mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    
    $count = 0;
    $rows  = array();
    while ( $row = mysql_fetch_array($results) ) {
      $rows[$row[0]] = $row[1];
      $count++;
    }
    return ($count)?$rows:false;
  }
  
  /**
   * Gets only the first Row as an associative array:
   * Array (
   *   id=1
   *   nick=levhita
   *   name=argel
   *   lastname=arias
   * )
   * @param string $sql
   * @return array
   */
  public function getRow($sql)
  {
    if ( !$results = @mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    
    if ( $row = mysql_fetch_assoc($results) ) {
      return $row;
    }
    return false;
  }
  
  /**
   * Gets only the first value of the first row
   * @param string $sql
   * @return string
   */
  public function getOne($sql)
  {
    if ( !$results = @mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    
    if ( $row = mysql_fetch_array($results) ) {
      return $row[0];
    }
    return false;
  }
  
  /**
   * Executes a query directly
   * @param string $sql
   * @return boolean
   */
  public function execute($sql)
  {
    if ( !@mysql_query($sql, $this->_db_connection) ) {
      throw new RunTimeException("Couldn't execute query: ". mysql_error($this->_db_connection) );
    }
    return true;
  }
  
  /**
   * Used when you need to know the id of the row that you just inserted
   * @return integer
   */
  public function getLastId()
  {
    return mysql_insert_id($this->_db_connection);
  }
  
  public function getLastError(){
  	return mysql_error();
  }
}
