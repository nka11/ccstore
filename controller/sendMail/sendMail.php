<?php
class sendMail {
	private $db_connect;
	public function __construct() {
		global  $db_string, $db_user, $db_password;
		$this->db_connect = new PDO($db_string, $db_user, $db_password);
	}
	//GET DAO
	public function loadDAO($table){
		$tableDAO = ucfirst($table)."DAO";
		return new $tableDAO($this->db_connect);
	}
}