<?php
session_start();
include("config.php");
ch();
require('http.php');
require('oauth_client.php');
require('database_oauth_client.php');
require('mysqli_oauth_client.php');
$client = new mysqli_oauth_client_class;
$client->database = array(
 'host'=>$host,
 'user'=>$usr,
 'password'=>$pass,
 'name'=>"open",
 'port'=>!isset($port) || $port=='' ? 3306:$port,
 'socket'=>'/var/lib/mysql/mysql.sock'
);
$client->server = 'Twitter';
$client->offline = true;
$client->debug = true;
$client->debug_http = true;
$client->client_id = 'client_id';
$client->client_secret = 'client_secret';
$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_twitter';
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
if($client->exit){ser("Error","Something Happened. Try again.");}
if($success){
 if(strlen($client->access_token)){
  header("Location:http://open.subinsb.com/home");
 }else{
  echo 'Error: ', $client->error, "\n";
 }
}
?>
