<?php

require_once './vendor/autoload.php';
require_once './model/class/Client.class.php';
use Pux\Mux;
class AbstractController extends \Pux\Controller {
  private $loader;
 
  private $twig;
  public $session;
  public $base_path;
  public function __construct() {
    //parent::__construct();
    $this->loader = new Twig_Loader_Filesystem('./templates');

    $this->twig = new Twig_Environment($this->loader, array(
//        'cache' => './template_cache',
      ));
    $this->handle_session();
    $this->base_path = substr($_SERVER['REQUEST_URI'],
      0,
      strripos($_SERVER['REQUEST_URI'],$_REQUEST['path']));
  }

  public function render($template, $data=[]) {
    $data['session'] = $this->session;
    $data['path'] = $_REQUEST['path'];
    $data['base_path'] = $this->base_path;
    return $this->twig->render($template,$data);
  }

  public function handle_session() {
    if(!isset($_SESSION))
      session_start();
    $this->session['statut'] = (empty($_SESSION['statut']))
      ? 'visitor'
      : $_SESSION['statut'];
    $this->session['user'] = (!empty($_SESSION['user']))
      ? $_SESSION['user'] 
      : new Client (  array(  'id_c'      =>  0,
                              'nom'     =>  'Visiteur',
                              'prenom'    =>  NULL,
                              'email'     =>  NULL,
                              'adresse'   =>  NULL,
                              'code_postal' =>  NULL,
                              'ville'     =>  NULL,
                              'departement' =>  NULL,
                              'telephone'   =>  NULL));
		switch($this->session['statut']){
			case 'visitor'  : 
					$this->session['admin_open']=false;
					$this->session['client_open']=false;
					$this->session['visitor_open']=true;
			break;
			case 'client' : 
					$this->session['admin_open']=false;
					$this->session['client_open']=true;
					$this->session['visitor_open']=false;
			break;
			case 'admin'  : 
					$this->session['admin_open']=true;
					$this->session['client_open']=false;
					$this->session['visitor_open']=false;
			break;
			default: break;

    }
  }
}
