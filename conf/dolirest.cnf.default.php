<?php
/* 
 * Do not modify this file.
 * copy dolirest.cnf.php.example to dolirest.cnf.php then adjust your settings
 */

if (is_file("conf/dolirest.cnf.php")) {
  include "./conf/dolirest.cnf.php";
} else {
  $dolibarr_api_url = "http://localhost/dolibarr/htdocs/api/index.php";
  $dolibarr_user_login = "admin";
  $dolibarr_user_password = "admin";
  $dolibarr_web_customer_catid = 1;
  $dolibarr_clientadherent_catid = 2;
}
