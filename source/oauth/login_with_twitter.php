<?php
/* OAuth Login With Twitter
 * Copyright 2012 Manuel Lemos
 * Copyright 2014 Subin Siby
 * For Posting on Twitter
 * Thank you Manuel Lemos
*/

session_start();
/* Include the files */
require "$docRoot/inc/oauth/http.php";
require "$docRoot/inc/oauth/oauth_client.php";
require "$docRoot/inc/oauth/database_oauth_client.php";
require "$docRoot/inc/oauth/mysqli_oauth_client.php";

/* We make an array of Database Details */
$databaseDetails = unserialize(DATABASE);
/* The PHP OAuth Library requires some special items in array, so we add that */
$databaseDetails["password"] = $databaseDetails["pass"];
$databaseDetails["socket"] = "/var/lib/mysql/mysql.sock";

$client = new mysqli_oauth_client_class;
$client->database = $databaseDetails;
$client->server = 'Twitter';
$client->offline = true;
$client->debug = false;
$client->debug_http = false;
$client->client_id = $GLOBALS['cfg']['twitter']['api_key'];
$client->client_secret = $GLOBALS['cfg']['twitter']['api_secret'];
$client->redirect_uri = Open::URL('/oauth/login_with_twitter');

if(($success = $client->Initialize())){
  if(($success = $client->Process())){
    if(strlen($client->authorization_error)){
      $client->error = $client->authorization_error;
      $success = false;
    }elseif(strlen($client->access_token)){
      $success = $client->CallAPI('https://api.twitter.com/1.1/account/verify_credentials.json', 'GET', array(), array('FailOnAccessError'=>true), $user);
      if($success){
        $success = $client->SetUser($who);
      }
    }
  }
  $success = $client->Finalize($success);
}
if($client->exit){
   $OP->ser("Error", "Something Happened. Try again.");
}
if($success){
   if(strlen($client->access_token)){
      $OP->redirect(O_URL . "/home");
   }else{
      echo 'Error: ', $client->error, "\n";
   }
}
?>
