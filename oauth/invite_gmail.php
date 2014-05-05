<?
include('config.php');ch();
include('http.php');
include('oauth_client.php');
$client = new oauth_client_class;
$client->server = 'Google';
$client->offline = false;
$client->debug = false;
$client->debug_http = true;
$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/invite_gmail';
$client->client_id = 'client_id';
$client->client_secret = 'client_secret';
$client->scope = 'https://www.google.com/m8/feeds/';
if(($success = $client->Initialize())){
 if(($success = $client->Process())){
  if(strlen($client->authorization_error)){
   $client->error = $client->authorization_error;
   $success = false;
  }elseif(strlen($client->access_token)){
   $success = $client->CallAPI('https://www.google.com/m8/feeds/contacts/default/full?max-results=2500','GET', array(), array('FailOnAccessError'=>true), $data);
  }
 }
 $success = $client->Finalize($success);
}
if($client->exit){
 ser("Something Happened","<a href='".$client->redirect_uri."'>Try Again</a>");
}
if($success){
 $sn=get("fname");
 $n=get("name",$who,false);
 $xml= new SimpleXMLElement($data);
 $xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
 $result = $xml->xpath('//gd:email');
 foreach($result as $title) {
  $m=$title->attributes()->address;
  $sql=$db->prepare("SELECT COUNT(id) FROM users WHERE username=?");
  $sql->execute(array($m));
  if($sql->rowCount()==0){
   $msg=$n." has sent you an inviation to join <a href='http://open.subinsb.com'>Open</a>.<br/>Open is an open source social network. It's growing by the support of developers and people like you. Please join <a href='http://open.subinsb.com'>Open</a> and enjoy Privacy like never before.<br/>You can connect with $n and hunderds of others.<br/><a href='http://open.subinsb.com/register'><button style='padding:5px 15px;'>Accept Invitation</button></a>&nbsp;&nbsp;&nbsp;<a href='http://open.subinsb.com/$who'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
   $sql=$db->prepare("INSERT INTO mails(email,sub,message) VALUES (?,?,?)");
   $sql->execute(array($m, "$sn Sent You An Invitation", $msg));
  }
 }
 redirect("http://open.subinsb.com/invite?gmail=success");
}
?>
