<?php

require_once './vendor/autoload.php';
class AbstractController {
  public $loader;
 
  public $twig;
  public function __construct() {
  $this->loader = new Twig_Loader_Filesystem('./templates');

  $this->twig = new Twig_Environment($this->loader, array(
      'cache' => './template_cache',
    ));
  }
}
