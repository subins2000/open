<?php
/* Uncomment the following to display PHP errors */
ini_set("display_errors", "on");

/* Define the Host. No / at the end */
define("HOST", "http://open.subinsb.com");

/* Host without protocol (http, https) */
define("CLEAN_HOST", "open.subinsb.com");

/* -- Database Configuration STARTS -- */

	/* For my localhost purpose, I do this : */
	$dbname = getenv('OPENSHIFT_GEAR_NAME');
	
	/* getenv() returns boolean false if the environmental var doesn't exist */
	if($dbname !== false){
		/* Not localhost. It's the real site (open.subinsb.com) */
		define("DATABASE", serialize(array(
			"host" => getenv('OPENSHIFT_MYSQL_DB_HOST'),
			"port" => getenv('OPENSHIFT_MYSQL_DB_PORT'),
			"name" => $dbname, /* The Database name is constant in both localhost and online (open) */
			"user" => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
			"pass" => getenv('OPENSHIFT_MYSQL_DB_PASSWORD')
		)));
	}else{
		/* It's localhost */
		define("DATABASE", serialize(array(
			"host" => "127.0.0.1",
			"port" => "3306",
			"name" => "open",
			"user" => "root",
			"pass" => "localhostPassword"
		)));
	}
	/* We serialize the DATABASE constant value becuase we can't make arrays as values */
/* -- Database Configuration ENDS -- */
?>