<?php
/* 
 * Do not modify this file.
 * copy database.cnf.php.example to database.cnf.php then adjust your settings
 */

if (is_file("conf/database.cnf.php")) {
  include "./conf/database.cnf.php";
} else {
  $db_string = "mysql:host=localhost;dbname=ccstore";
  $db_user = "ccstore";
  $db_password = "ccstore";
}
