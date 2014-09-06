<?php
$docRoot = realpath(__DIR__);
define("docRoot", $docRoot);

/* Load the configuration */
require_once "$docRoot/config.php";
require_once "$docRoot/inc/class.open.php";
require_once "$docRoot/inc/class.logsys.php";
$LS = new LoginSystem();

/* Basic Variables */
$loggedIn = $LS->loggedIn; /* Boolean on status of current user (logged in or not) */
$who = $LS->user; /* The current user */

/* Global Variables */
$_P = count($_POST) > 0 ? true : false; /* Boolean Variable whether POST data is sent with the request */
define("loggedIn", $loggedIn);
define("curUser", $who);

$OP = new Open();

if(!function_exists("get")){
	function get($key, $uid = "", $json = true){
		global $OP;
		return $OP->get($key, $uid, $json);
	}
}
/* Do these if user is logged in */
if( loggedIn && !isset($uimg) ){
	$uimg  = get("img");
	$uaimg = get("avatar");
	$uname = get("name", "", false);
	/* Update the last seen time */
	$OP->save("seen");
}
?>