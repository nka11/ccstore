<?php
require_once("./model/CategoryDAO.php");
class CategoryDAOTest extends PHPUnit_Framework_TestCase{
	public function testGetCategories(){
		$cdao = new CategoryDAO();
		$categories = $cdao->getCategories();
		$this->assertNotInternalType('boolean', $categories);
		foreach ($categories as $category) {
			$this->assertInternalType('string',$category->label());
		}
	}
	// NE FONCTIONNE PAS
	public function testGetCategoryByLabel(){
		$cdao = new CategoryDAO();
		$category = $cdao->getCategoryByLabel("Boulangerie");
		$this->assertNotInternalType('boolean', $categories);
		$this->assertEquals("Boulangerie", $category->label());
	}
}