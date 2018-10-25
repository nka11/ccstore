<?php
include_once './vendor/autoload.php';
include './conf/database.cnf.default.php';

class AbstractClient{
	public $pdo_db;
	public $tb_prefix;
	
	  public function __construct($db_connect){
		$this->tb_prefix= "";
		$this->pdo_db = $db_connect;
		$this->pdo_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	  }
}
