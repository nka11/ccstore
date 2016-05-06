<?php

require_once './vendor/autoload.php';
require_once './controller/AbstractController.php';
class ErrorController extends AbstractController {
  /**
   * @Route("/404")
   * @Method("GET")
   */
  function notFoundAction() {
    http_response_code(404);
    return parent::render('error/404.html');
  }
}
