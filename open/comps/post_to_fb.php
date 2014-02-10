<?
function post_to_fb($t,$who,$pr){
 global$host;
 global$usr;
 global$pass;
 global$port;
 if($pr=='pub'){
  $prr=array('value'=>'EVERYONE');
 }
 if($pr=='fri'){
  $prr=array('value'=>'ALL_FRIENDS');
 }
 if($pr=='meo'){
  $prr=array('value'=>'CUSTOM','friends'=> "SELF");
 }
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
 $client->server = 'Facebook';
 $client->offline = true;
 $client->debug = false;
 $client->debug_http = false;
 $client->client_id = '670472332987070';
 $client->client_secret = '894580f515279de8238c5a5ae87b2884';
 if(($success=$client->Initialize())){
  $success=$client->CallAPI(
   "https://graph.facebook.com/me/feed",
   'POST',
   array(
    'message'=>$t,
    'privacy' => $prr
   ),array(
    'FailOnAccessError'=>true
   ),$user);
 }
 $success = $client->Finalize($success);
}
?>
