<?php
session_start();
include('config.php');
include('http.php');
include('oauth_client.php');
$host=parse_url($_GET['c']);
if(($_GET['c']=='' && $_SESSION['continue']=='') || $host['host']!='open.subinsb.com'){
 $_SESSION['continue']="http://open.subinsb.com";
}else{
 $_SESSION['continue']=$_GET['c'];
}
$client = new oauth_client_class;
$client->server = 'Google';
$client->offline = false;
$client->debug = false;
$client->debug_http = true;
$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_google';
$client->client_id = 'client_id';
$client->client_secret = 'client_secret';
$client->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/plus.me';
if(($success = $client->Initialize())){
 if(($success = $client->Process())){
  if(strlen($client->authorization_error)){
   $client->error = $client->authorization_error;
   $success = false;
  }elseif(strlen($client->access_token)){
   $success = $client->CallAPI('https://www.googleapis.com/oauth2/v1/userinfo','GET', array(), array('FailOnAccessError'=>true), $user);
  }
 }
 $success = $client->Finalize($success);
}
if($client->exit){
 ser("Something Happened","<a href='".$client->redirect_uri."'>Try Again</a>");
}
if($success){
 $loc=$_SESSION['continue'];
 $m=$user->email;
 $n=$user->name;
 $g=$user->gender;
 $b=date('d/m/Y', strtotime($user->birthday));
 $i=$user->picture;
 $sql=$db->prepare("SELECT * FROM users WHERE username=?");
 $sql->execute(array($m));
 $tme=time()*60+1200;
 if($sql->rowCount()!=0){
  while($r=$sql->fetch()){$id=$r['id'];}
  setcookie("curuser", $id, $tme, "/", $_SERVER['HTTP_HOST']);
  setcookie("wervsi", encrypter($id), $tme, "/", $_SERVER['HTTP_HOST']);
  header("Location:$loc");
 }else{
  function ras($length){$chars='q!f@g#h#n$m%b^v&h*j(k)q_-=jn+sw47894swwfv1h36y8re879d5d2sd2sdf55sf4rwejeq093q732u4j4320238o/.Qkqu93q324nerwf78ew9q823';$size=strlen($chars);for($i=0;$i<$length;$i++){$str.=$chars[rand(0,$size-1)];}return$str;}
  $r_salt=ras(25);
  $site_salt="a_salt_key";
  $salted_hash=hash('sha256',"a_password".$site_salt.$r_salt);
  $json='{"joined":"'.date("Y-m-d H:i:s").'","gen":"'.$g.'","birth":"'.$b.'","img":"'.$i.'"}';
  $sql=$db->prepare('INSERT INTO users (username,password,psalt,name,udata)VALUES(:us,:ps,:s,:n,:u)');
  $sql->bindParam(":us",$m);
  $sql->bindParam(":ps",$salted_hash);
  $sql->bindParam(":s",$r_salt);
  $sql->bindParam(":n",$n);
  $sql->bindParam(":u",$json);
  $sql->execute();
  $sql=$db->prepare("SELECT * FROM users WHERE username=?");
  $sql->execute(array($m));
  while($r=$sql->fetch()){$id=$r['id'];}
  setcookie("curuser", $id, $tme, "/", $_SERVER['HTTP_HOST']);
  setcookie("wervsi", encrypter($id), $tme, "/", $_SERVER['HTTP_HOST']);
  header("Location:$loc");
 }
}
else{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Error</title>
</head>
<body>
<h1>OAuth client error</h1>
<pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
</body>
</html>
<?}?>
