<?php
/* Uncomment the following to display PHP errors */
ini_set("display_errors", "on");

/* Define the Host. No / at the end */
define("HOST", "http://open.subinsb.com");

/* Host without protocol (http, https) */
define("CLEAN_HOST", "open.subinsb.com");

/* -- Database Configuration STARTS -- */
  
define("DATABASE", serialize(array(
  "host" => getenv('OPENSHIFT_MYSQL_DB_HOST'),
  "port" => getenv('OPENSHIFT_MYSQL_DB_PORT'),
  "name" => "open",
  "user" => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
  "pass" => getenv('OPENSHIFT_MYSQL_DB_PASSWORD')
)));
    
/* We serialize the DATABASE constant value becuase we can't make arrays as values */
  
/* -- Database Configuration ENDS -- */
?>
