<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class ErrorController extends AbstractController {
  /**
   * @Route("/404(/:e)")
   * @Method("GET")
   */
  function notFoundAction($e="") {
    http_response_code(404);
    return parent::render('error/404.html', array("e" => $e));
  }
}
