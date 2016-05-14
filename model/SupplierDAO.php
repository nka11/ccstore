<?php
require_once("./model/AbstractRestClient.php");
require_once("./model/class/Supplier.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class SupplierDAO extends AbstractRestClient {
	public function getSuppliers(){
		/*DO SOMETHING*/
	}
	
	public function getSupplierById($id){
		/*DO SOMETHING*/
	}
	
	public function createSupplier(Supplier $supplier){
		/*DO SOMETHING*/
	}
	public function updateSupplier(Supllier $supplier){
		/*DO SOMETHING*/
	}
	public function deleteSupplier(Supplier $supplier){
		/*DO SOMETHING*/
	}
	private function _mapSupplier($data) {
		$supplier = new Supplier(array(
		  "id" => (int)$data->id,
		  "name" => $data->name,
		  "name_alias" => $data->name_alias,
		  "address" => $data->adress,
		  "zip" => $data->zip,
		  "town" => $data->town,
		));
		return $supplier;
  }
}