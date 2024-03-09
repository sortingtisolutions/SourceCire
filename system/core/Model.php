<?php
defined('BASEPATH') or exit('No se permite acceso directo');
/**
 * Modelo base
 */
class Model
{
  /**
  * @var object
  */
  protected $db;
 
  /**
  * Inicializa conexion
  */
  public function __construct()
  {
    // $numr = mt_rand(0,4);
    // echo "El numero es :" . $numr . " Aleatorio";
    $numr = 0;
    IF ($numr == 0){
      $this->db = new Mysqli(HOST, USER, PASSWORD, DB_NAME);
      $this->db->set_charset("utf8");
      define('USEDUSER',USER);
    }
    IF ($numr == 1){
      $this->db = new Mysqli(HOST1, USER1, PASSWORD1, DB_NAME1);
      $this->db->set_charset("utf8");
      define('USEDUSER',USER1);
    }
    IF ($numr == 2){
      $this->db = new Mysqli(HOST2, USER2, PASSWORD2, DB_NAME2);
      $this->db->set_charset("utf8");
      define('USEDUSER',USER2);
    }
    IF ($numr == 3){
      $this->db = new Mysqli(HOST3, USER3, PASSWORD3, DB_NAME3);
      $this->db->set_charset("utf8");
      define('USEDUSER',USER3);
    }

  }
 
  /*** Finaliza conexion */
  public function __destruct()
  {
    
     $this->db->close();

  }


}