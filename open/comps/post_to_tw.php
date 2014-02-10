<?
require("$sroot/oauth/http.php");
require("$sroot/oauth/oauth_client.php");
require("$sroot/oauth/database_oauth_client.php");
require("$sroot/oauth/mysqli_oauth_client.php");
function post_to_tw($t,$who){
 global$host;
 global$usr;
 global$pass;
 global$port;
 $client = new mysqli_oauth_client_class;
 $client->user = $who;
 $client->database = array(
  'host'=>$host,
  'user'=>$usr,
  'password'=>$pass,
  'name'=>"open",
  'port'=>$port=='' ? 3306:$port,
  'socket'=>'/var/lib/mysql/mysql.sock'
 );
 $client->server = 'Twitter';
 $client->offline = true;
 $client->debug = false;
 $client->debug_http = true;
 $client->client_id = 'IrZGE5zMhO2W2wsyHjwNyQ';
 $client->client_secret = 'LVws3Gke5kOAHJvVTxKSJQpCqBkpVi13isEnRdm8';
 if(($success=$client->Initialize())){
  $success=$client->CallAPI(
   "https://api.twitter.com/1.1/statuses/update.json",
   'POST',
   array(
    'status'=>$t,
   ),array(
    'FailOnAccessError'=>true
   ),$user);
 }
 $success = $client->Finalize($success);
}
?>
