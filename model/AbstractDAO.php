<?php
abstract class AbstractDAO {
  public $bdd;
  public function initBdd(
    string $dbstring='mysql:host=localhost;dbname=dolib_test',
    string $dbuser='testuser',
    string $dbpass='testpass' 
  ) {
    $this->bdd = new PDO($dbstring,$dbuser,$dbpass);
    $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $this->bdd->exec("SET CHARACTER SET utf8");
  }

  public function setBdd(PDO $bdd) {
    $this->bdd = $bdd;
  }
}
?>
