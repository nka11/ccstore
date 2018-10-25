<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Product.class.php");
include_once './vendor/autoload.php';

class ProductDAO extends AbstractClient {
	public function getProducts(){
		$result = array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."product";
		$req_string.= " ORDER BY label ASC";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapProduct($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	public function getProductsFilters($visibility=1, $availability=1){
		$result = array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."product";
		$req_string.= " WHERE visibility=$visibility AND availability=$availability";
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapProduct($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	 /**
   * @param id $id Id produit a récupérer
   */
  public function getProductById($id) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."product WHERE rowid=:id");
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapProduct($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function getProductByRef($ref) {
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."product WHERE ref=:ref");
	$req->bindValue(':ref', $ref);
	$req->execute();
	if ($req->rowCount() == 1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapProduct($data);
		}
			return $result;
	}else{
		return false;
	}
  }
  public function getProductsByCategory($id_cat){
	  $result=array();
	  $req=$this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."product WHERE fk_cat=:fk_cat");
	  $req->bindValue(':fk_cat', $id_cat, PDO::PARAM_INT);
	  $req->execute();
	  if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapProduct($data);
			}
		}
		return $result;
  }
  
  public function getProductsBySupplier($fk_supplier){
	  $result=array();
	  $req= $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."product WHERE fk_supplier=".$fk_supplier);
	  $req->execute();
	  if ($req->rowCount() >= 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapProduct($data);
			}
			return $result;
		}
		else {
			return false;
		}
  }
  
  public function getProductByLabel($label){
	$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."product WHERE label='$label'");
	$req->execute();
	 foreach($req->errorInfo() as $e)
		{
			if ($e!= 0 ) echo $e;
		}
	if($req->rowCount()==1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapProduct($data);
		}
		return $result;
	}else{
		return false;
	}
  }
  
   public function createProduct(Product $product){
		$req = $this->pdo_db->prepare("INSERT INTO ".$this->tb_prefix."product SET  fk_supplier=:fk_supplier, label=:label, ref=:ref, agriculture=:agriculture, price=:price, supplier_price=:supplier_price,package_weight=:package_weight, weight_unit= :weight_unit, packaging=:packaging, fk_cat=:fk_cat, description=:description, visibility=:visibility, availability=:availability");
		$req->bindValue(':fk_supplier', $product->fk_supplier(), PDO::PARAM_INT);
		$req->bindValue(':label', utf8_decode($product->label()));
		$req->bindValue(':ref', $product->ref());
		$req->bindValue(':agriculture', utf8_decode($product->agriculture()));
		$req->bindValue(':price', $product->price());
		$req->bindValue(':supplier_price', $product->supplier_price());
		$req->bindValue(':package_weight', $product->package_weight(), PDO::PARAM_INT);
		$req->bindValue(':weight_unit', $product->weight_unit());
		$req->bindValue(':packaging', $product->packaging());
		$req->bindValue(':fk_cat', $product->fk_cat(), PDO::PARAM_INT);
		$req->bindValue(':description', $product->description());
		$req->bindValue(':visibility', $product->visibility(), PDO::PARAM_INT);
		$req->bindValue(':availability', $product->availability(), PDO::PARAM_INT);
		$req->execute();
		
		$confirm = $this->getProductByRef($product->ref());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  
  public function updateProduct(Product $product){
	  $req= $this->pdo_db->prepare("UPDATE ".$this->tb_prefix."product SET fk_supplier=:fk_supplier, label=:label, ref=:ref, agriculture=:agriculture, price=:price, supplier_price=:supplier_price, package_weight=:package_weight, weight_unit= :weight_unit, packaging=:packaging, fk_cat=:fk_cat, description=:description, visibility=:visibility, availability=:availability WHERE rowid=:id");
	  $req->bindValue(':fk_supplier', $product->fk_supplier(), PDO::PARAM_INT);
	  $req->bindValue(':label', $product->label());
	  $req->bindValue(':ref', $product->ref());
	  $req->bindValue(':agriculture', $product->agriculture());
	  $req->bindValue(':price', $product->price());
	  $req->bindValue(':supplier_price', $product->supplier_price());
	  $req->bindValue(':weight_unit', $product->weight_unit());
	  $req->bindValue(':package_weight', $product->package_weight(), PDO::PARAM_INT);
	  $req->bindValue(':packaging', $product->packaging());
	  $req->bindValue(':fk_cat', $product->fk_cat(), PDO::PARAM_INT);
	  $req->bindValue(':description', $product->description());
	  $req->bindValue(':visibility', $product->visibility(), PDO::PARAM_INT);
	  $req->bindValue(':availability', $product->availability(), PDO::PARAM_INT);
	  $req->bindValue(':id', $product->id(), PDO::PARAM_INT);
	  $req->execute();
		
		$confirm = $this->getProductByRef($product->ref());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  
  public function deleteProduct(Product $product){
	  $req= $this->pdo_db->exec("DELETE FROM ".$this->tb_prefix."product WHERE rowid=".$product->id());
  }
  
  public function mapProduct($data){
		$product = new Product(array(
			"id"=>(int)$data['rowid'],
			"label"=> html_entity_decode($data['label']),
			"ref"=> $data['ref'],
			"agriculture"=>html_entity_decode($data['agriculture']),
			"price"=>$data['price'],
			"supplier_price"=>$data['supplier_price'],
			"package_weight"=>(int)$data['package_weight'],
			"weight_unit"=>$data['weight_unit'],
			"packaging"=>html_entity_decode($data['packaging']),
			"fk_cat"=>(int)$data['fk_cat'],
			"fk_supplier"=>(int)$data['fk_supplier'],
			"description" => html_entity_decode($data['description']),
			"visibility"=>$data['visibility'],
			"availability"=>$data['availability']
	  ));
	  return $product;
  }

}