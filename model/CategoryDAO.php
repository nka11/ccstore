<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Category.class.php");
include_once './vendor/autoload.php';
use \nategood\httpful;
use \Httpful\Request;
class CategoryDAO extends AbstractClient {
  public function getCategories() {
    $result = array();
    $req = $this->pdo_db->prepare('SELECT * FROM category');
	$req->execute();
	if($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapCategory($data);
			}
	}
	return $result;
  }
  /**
   * @param id $id Id category a récupérer
   */
  public function getCategoryById($id) {
    $req = $this->pdo_db->prepare('SELECT * FROM category WHERE rowid=:id');
	$req->bindValue(':id', $id, PDO::PARAM_INT);
	$req->execute();
	if($req->rowCount()==1){
		while($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapCategory($data);	
		}
	return $result;
	}
	else{
		return false;
	}
  }
  
  public function getCategoryByLabel($label){
	$req = $this->pdo_db->prepare("SELECT * FROM category WHERE cat_label=:label");
	$req->bindValue(':label', $label);
	$req->execute();
	if($req->rowCount()==1){
		while ($data = $req->fetch(PDO::FETCH_ASSOC)){
			$result = $this->mapCategory($data);
		}
		return $result;
	}else{
		return false;
	}
  }
  
  public function getCategoriesByParent($id_parent){
		$result = array();
		$req = $this->pdo_db->prepare('SELECT * FROM category WHERE fk_cat='.$id_parent);
		$req->execute();
		if($req->rowCount() > 0){
				while($data = $req->fetch(PDO::FETCH_ASSOC)){
					$result[] = $this->mapCategory($data);
				}
		}
		return $result;
  }
  
  public function createCategory(Category $category){
	  $existing = $this->getCategoryByLabel($category->label());
		// a category exist with that label, returning null
		if ($existing != false) {
		  return false;
		}
		$req = $this->pdo_db->prepare('INSERT INTO category SET fk_cat=:fk_cat, cat_label=:label, cat_description=:description');
		$req->bindValue(':fk_cat', $category->id_parent());
		$req->bindValue(':label', $category->label());
		$req->bindValue(':description', $category->description());
		$req->execute();
		
		$confirm = $this->getCategoryByLabel($category->label());
		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  public function updateCategory(Category $category){
	  
		$req = $this->pdo_db->prepare('UPDATE category SET fk_cat=:fk_cat, cat_label=:label, cat_description=:description WHERE rowid=:id');
		$req->bindValue(':fk_cat', $category->id_parent());
		$req->bindValue(':label', $category->label());
		$req->bindValue(':description', $category->description());
		$req->bindValue(':id', $category->id(), PDO::PARAM_INT);
		$req->execute();
		
		$confirm = $this->getCategoryByLabel($category->label());

		if($confirm != false){
			return $confirm;
		}
		else{
			return false;
		}
  }
  
  private function mapCategory($data) {
    $category = new Category(array(
      "id" => (int)$data['rowid'],
      "id_parent" => $data['fk_cat'],
      "label" => html_entity_decode($data['cat_label']),
      "description" => html_entity_decode($data['cat_description'])
    ));
    return $category;
  }

}
