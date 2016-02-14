<?php
/* A note to programmers - for comments don't use // and use only star-slash ones */

require_once __DIR__ . "/load.php";

/* This is not needed if Open is in site's document root, but needed if Open is in a sub folder
---------------------
// Make the request URL relative to the base URL of Lobby installation. http://localhost/open will be changed to "/" and http://open.local to "/"
$lobbyBase = str_replace($_SERVER['DOCUMENT_ROOT'], "", $docRoot);
$_SERVER['REQUEST_URI'] = str_replace($lobbyBase, "", $_SERVER['REQUEST_URI']);
* -------------------
*/

require_once "$docRoot/inc/vendor/autoload.php";
$router = new \Klein\Klein();

function makeSource($loc){
  global $OP, $docRoot, $LS, $who;
  global $_P, $uname, $uimg; // Extra Vars
  $_SERVER['REDIRECT_PAGE'] = "/$loc";
  include docRoot . "/source/$loc.php";
}
function idPage($file, $request){
  if( is_numeric($request->id) ){
    $_GET['id'] = $request->id;
  }
  $GLOBALS['itsMyPage'] = true;
  makeSource("$file");
}

/* The default 404 pages */
$router->respond('*', function($request, $response) use($OP) {
  if($response->status() == "400"){
    $OP->ser();
  }
});

/* Routing Pages */
$router->respond("/", function() {
   makeSource("index");
   $GLOBALS['itsMyPage'] = true;
});
$router->respond("/me", function() {
   makeSource("me/index");
   $GLOBALS['itsMyPage'] = true;
});

/* START - Recurring Dynamic Pages */
$router->respond("/?[a:ID]?/[a:page]?", function($request, $response) {
   if( is_numeric($request->ID) || $request->ID == "profile" ){
    $_GET['part'] = $request->page;
    $_GET['id'] = $request->ID;
    makeSource("profile");
    $GLOBALS['itsMyPage'] = true;
  }
});
$router->respond("/chat?/[i:id]?", function($request, $response) {
   idPage("chat", $request);
});
$router->respond("/view?/[i:id]?", function($request, $response) {
   idPage("view", $request);
});
$router->respond("/search/?[**:query]?", function($request, $response) {
   if( $_SERVER['REDIRECT_URL'] == "/search" && isset($_GET['q']) ){
    $encodedQuery = Open::encodeQuery($_GET['q']);
    $url = Open::URL("/search/{$encodedQuery}");
    $response->redirect($url);
  }else{
    $_GET['q'] = $request->query;
    makeSource("search");
  }
   $GLOBALS['itsMyPage'] = true;
});
$router->respond("/data/?[**:path]?", function($request, $response) {
   $_GET['request'] = $request->path;
   makeSource("data/handle");
   $GLOBALS['itsMyPage'] = true;
});
/* END - Dynamic Pages */

$router->respond("/css/[**:files]", function($request, $response) use($docRoot) {
   $_GET['f'] = $request->files;
   include "$docRoot/cdn/css/get.php";
   $GLOBALS['itsMyPage'] = true;
});
$router->respond("/js/[**:files]", function($request, $response) use($docRoot) {
   $_GET['f'] = $request->files;
  include "$docRoot/cdn/js/get.php";
  $GLOBALS['itsMyPage'] = true;
});

$router->respond("/url?/[**:url]?", function($request, $response) use($docRoot) {
  $response->redirect($request->url);
});

/**
 * Open Auth (Opth)
 */
$router->respond("/opth/api/users/[*:token]?/[**:what]", function($request, $response) use($docRoot, $OP) {
  $user_token = $request->token;
  $what = $request->what;

  include "$docRoot/source/opth/api/users.php";
  $GLOBALS['itsMyPage'] = true;
});

$router->respond("/[**:path]", function($request, $response) use($docRoot, $OP) {
   $loc = docRoot . "/source/{$request->path}.php";
   if ( !isset($GLOBALS['itsMyPage']) ){
    if( file_exists($loc) ){
      makeSource($request->path);
    }else{
      $response->code(404);
      $OP->ser();
      return false;
    }
  }
});
/* End Routing for Pages */
/* Finish the Routing */
$router->dispatch();
?>
