<?php
require_once("./model/AbstractClient.php");
require_once("./model/class/Recipe.class.php");
include_once './vendor/autoload.php';

class RecipeDAO extends AbstractClient {
	
	public function getRecipes(){
		$result = array();
		$req_string= "SELECT * FROM ".$this->tb_prefix."recipe";
		/*$req_string.= (!$status)
							?	" WHERE status= 'visible'"
							:	"";*/
		$req = $this->pdo_db->prepare($req_string);
		$req->execute();
		
		if ($req->rowCount() > 0){
			while($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result[] = $this->mapRecipe($data);
			}
			return $result;
		}
		else {
			return false;
		}
	}
	/**
     * @param id $id Id recipe a récupérer
     */
	public function getRecipeById($id){
		$req = $this->pdo_db->prepare("SELECT * FROM ".$this->tb_prefix."recipe WHERE rowid=:id");
		$req->bindValue(':id', $id, PDO::PARAM_INT);
		$req->execute();
		if ($req->rowCount() == 1){
			while ($data = $req->fetch(PDO::FETCH_ASSOC)){
				$result = $this->mapRecipe($data);
			}
				return $result;
		}else{
			return false;
		}
	}
	/**
	 * @param recipe
	 */
}