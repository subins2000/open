<?
/* A note to programmers - for comments don't use // and use only star-slash ones */
include_once "inc/load.php";
include_once "inc/class.pages.php";
$OP=new OpenPages(); /* Because OpenPages class extends from main class Open */
$requestURI      = isset($_GET['request']) ? $_GET['request'] : "";
$requestURIParts = explode("/", $requestURI);
/* Does the array have more than 1 value */
if(count($requestURIParts) > 1){
 	/* Get  2 parts of the request URI */
 	list($requestURIPart1, $requestURIPart2) = $requestURIParts;
 	/* Remove .php extension */
 	$requestURIPart2=substr($requestURIPart2, -4, 4)==".php" ? substr_replace($requestURIPart2, "", -4, 4):$requestURIPart2;
}else{
 	/* Only 1 part of URI is available */
 	list($requestURIPart1)=$requestURIParts;
 	$requestURIPart2="";
}
/* Some changes and get it to Open */
$_SERVER['PHP_SELF']="/".$requestURI;
$OP->definedVars=get_defined_vars();

/* Does the file exists ? We make it a variable because we use it multiple times */
$requestURIExist=$OP->exists($requestURI);
if($requestURI!=""){
 	if($OP->makeProfilePage($requestURIPart1, $requestURIPart2)){
 	}elseif($OP->makeViewPage($requestURIPart1, $requestURIPart2)){
 	}elseif($OP->makeChatPage($requestURIPart1, $requestURIPart2)){
 	}elseif($OP->makeFindPage($requestURIPart1, $requestURIPart2)){
 	}elseif($OP->makeDataPage($requestURIPart1, $requestURI)){
 	}elseif($OP->makeRedirects($requestURIPart1, $requestURIPart2, $requestURI)){
 	}elseif($requestURIExist){
  		if(is_dir($OP->sourceDir."/".$requestURI)){
  			$OP->incPage($requestURI."/index.php");
  		}else{
  			$OP->incPage($requestURI);
  		}
 	}else{
  		$OP->ser();
 	}
}else{
 	$OP->init();
 	$OP->incPage("index.php");
}
?>