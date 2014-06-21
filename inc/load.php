<?
if(!isset($OP)){
 $thisFolderRoot = realpath(dirname(__FILE__));
 require "$thisFolderRoot/../config.php";
 require "$thisFolderRoot/class.open.php";
 $OP = new Open();
}
/* Basic Variables */
$lg   = $OP->lg;  /* Boolean on status of current user (logged in or not) */
$who  = $OP->uid; /* The current user */
$whod = $OP->sid; /* The encrypted cookie value */

if(!function_exists("get")){
 function get($k, $u="", $j=true){
  global $OP;
  return $OP->get($k, $u, $j);
 }
}
/* Do these if user is logged in */
if($OP->lg && !isset($uimg)){
 $uimg  = get("img");
 $uaimg = get("avatar");
 $uname = get("name", "", false);
 /* Update the last seen time */
 $OP->save("seen");
}

/* Global Variables */
/* Boolean Variable whether POST data is sent with the request */
$_P=count($_POST) > 0 ? true:false;

/* The document root (eg: /var/www/open) */
$docRoot=$OP->root;

/* Let Open know the defined variables */
$OP->definedVars=get_defined_vars();
?>