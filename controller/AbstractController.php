<?php
require_once './vendor/autoload.php';
require_once './controller/dbManager/dbManager.php';
use Pux\Mux;
class AbstractController extends \Pux\Controller {
  private $loader;
  private $twig;
  protected $db_connect;
  protected $dbManager;
  public $orderblock;				//  ...> ( sent to cManger)
  public $session;
  public $base_path;
  
   public function __construct() {
    global  $db_string, $db_user, $db_password;
	$this->loader = new Twig_Loader_Filesystem('./templates');

    $this->twig = new Twig_Environment($this->loader, array(
			'debug'=>true
//        'cache' => './template_cache',
      ));
	  //$twig->getExtension('Twig_Extension_Core')->setNumberFormat(2, '.', ',');
	  //$this->twig->addExtension(new Twig_Extension_Debug());
	$this->dbManager= new DbManager();
	$this->handle_session(); 	//
	$this->setOrderblock();		//
    $this->base_path = substr($_SERVER['REQUEST_URI'],
      0,
      strripos($_SERVER['REQUEST_URI'],$_REQUEST['path']));
	
   }
   
    public function render($template, $data=[]) {
		$data['session'] = $this->session;
		$data['path'] = $_REQUEST['path'];
		$data['base_path'] = $this->base_path;
		$data['orderblock'] = $this->orderblock;
		return $this->twig->render($template,$data);
  }
  
  public function handle_session() {
	  if(empty($_SESSION)){
		session_start();
	  }
	  $temp_request= (empty($_SESSION['temp_request'])) // use in pw forgotten
	  ?	null
	  :	$_SESSION['temp_request'];
      $this->session['status'] = (empty($_SESSION['status']))
      ? 'visitor'
      : $_SESSION['status'];
      $this->session['user'] = (!empty($_SESSION['user']))
      ? $_SESSION['user']
      : null;
	$this->session['basket'] = (!empty($_SESSION['basket']))
	 ? $_SESSION['basket']
	 : array();
	 $this->session['basket_amount']=NULL;
	 foreach($this->session['basket'] as $line){
		$this->session['basket_amount']+= number_format($line['value'],2);
	}
	$_SESSION = $this->session;
	$_SESSION['temp_request'] = (!$temp_request) // asked in pw forgotten
	? null
	: $temp_request;
  }
  public function setOrderblock(){
		$closing= new DateTime(date("Y-m-d"));
		$opening= new DateTime(date("Y-m-d"));
		$closing_interval= ( date("N") < 4 )
										? "+". (3 - date("N")) . " day"
										:  "+". (10 - date("N")) . " day";
		$opening_interval= ( date("N") < 4 )
										?	"-". (3 + date("N")) . " day"
										:	"-". (date("N") - 4) . " day";
		$week_delivery= ( date("N") < 4 )
										? date("W")
										:  date("W") + 1;
		$closing->modify($closing_interval);
		$opening->modify($opening_interval);
		$this->orderblock= array(
								"week"		=>	$week_delivery,
								"opening"	=>	$opening->format("Y-m-d"),
								"closing"	=>	$closing->format("Y-m-d")
								);
  }
}