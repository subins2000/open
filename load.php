<?php
$docRoot = realpath(__DIR__);
define("docRoot", $docRoot);

/**
 * Load the configuration
 */
require_once "$docRoot/config.php";
$GLOBALS['cfg'] = $cfg;

require_once "$docRoot/inc/class.open.php";
require_once "$docRoot/inc/class.logsys.php";

$db = unserialize(DATABASE);
\Fr\LS::config(array(
  "basic" => array(
    "email_callback" => function($email, $subject, $body){
      \Open::sendEMail($email, $subject, $body);
    }
  ),
  "db" => array(
    "host" => $db['host'],
    "port" => $db['port'],
    "name" => $db['name'],
    "username" => $db['user'],
    "password" => $db['pass'],
    "table" => "users"
  ),
  /**
   * Keys used for encryption
   * DONT MAKE THIS PUBLIC
   */
  "keys" => array(
    /**
     * Changing cookie key will expire all current active login sessions
     */
    "cookie" => $cfg["logsys"]["cookie_key"],
    /**
     * `salt` should not be changed after users are created
     */
    "salt" => $cfg["logsys"]["password_salt"]
  ),
  "pages" => array(
    "no_login" => array(
      "/",
      "/register",
      "/me/ResetPassword"
    ),
    "login_page" => "/login",
    "home_page" => "/home"
  ),
  "features" => array(
    "email_login" => true
  ),
  "cookies" => array(
    "domain" => CLEAN_HOST
  )
));

/* Basic Variables */
$loggedIn = \Fr\LS::$loggedIn; /* Boolean on status of current user (logged in or not) */
$who = \Fr\LS::$user; /* The current user */

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
  $uimg = get("img");
  $uaimg = get("avatar");
  $uname = get("name", "", false);
  /* Update the last seen time */
  $OP->save("seen");
}
?>
