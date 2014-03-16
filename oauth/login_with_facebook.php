<?php
session_start();
include('config.php');
require('http.php');
require('oauth_client.php');
require('database_oauth_client.php');
require('mysqli_oauth_client.php');
function ras($length){$chars='q!f@g#h#n$m%b^v&h*j(k)q_-=jn+sw47894swwfv1h36y8re879d5d2sd2sdf55sf4rwejeq093q732u4j4320238o/.Qkqu93q324nerwf78ew9q823';$size=strlen($chars);for($i=0;$i<$length;$i++){$str.=$chars[rand(0,$size-1)];}return$str;}
$_GET['c']=isset($_GET['c']) ? $_GET['c']:"";
$hostp=parse_url($_GET['c']);
$hostp['host']=isset($hostp['host']) ? $hostp['host']:"";
$_SESSION['continue']=isset($_SESSION['continue']) ? $_SESSION['continue']:"";
if(($_GET['c']=='' && $_SESSION['continue']=='') || $hostp['host']!='open.subinsb.com'){
 $_SESSION['continue']="http://open.subinsb.com/home";
}else{
 $_SESSION['continue']=$_GET['c'];
}
$client = new mysqli_oauth_client_class;
$client->database = array(
 'host'=>$host,
 'user'=>$usr,
 'password'=>$pass,
 'name'=>"open",
 'port'=>!isset($port) || $port=='' ? 3306:$port,
 'socket'=>'/var/lib/mysql/mysql.sock'
);
$client->server = 'Facebook';
$client->offline = true;
$client->debug = false;
$client->debug_http = true;
$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_facebook';
$client->client_id = 'client_id';
$client->client_secret = 'client_secret';
$client->scope = 'email,user_about_me,user_birthday,user_location,publish_stream,status_update';
if(($success = $client->Initialize())){
 if(($success = $client->Process())){
  if(strlen($client->authorization_error)){
   $client->error = $client->authorization_error;
   $success = false;
  }elseif(strlen($client->access_token)){
   $success = $client->CallAPI('https://graph.facebook.com/me', 'GET', array(), array('FailOnAccessError'=>true), $user);
   if($success){
    $loc=$_SESSION['continue'];
    $m=$user->email;
    $n=$user->name;
    $g=$user->gender;
    $b=date('d/m/Y', strtotime($user->birthday));
    $i=get_headers("https://graph.facebook.com/me/picture?width=200&height=200&access_token=".$client->access_token,1);
    $i=$i['Location'];
    $sql=$db->prepare("SELECT * FROM users WHERE username=?");
    $sql->execute(array($m));
    $tme=time()*60+1200;
    if($sql->rowCount()!=0){
     while($r=$sql->fetch()){$id=$r['id'];}
     setcookie("curuser", $id, $tme, "/", $_SERVER['HTTP_HOST']);
     setcookie("wervsi", encrypter($id), $tme, "/", $_SERVER['HTTP_HOST']);
     $client->SetUser($id);
     header("Location:$loc");
    }else{
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
     $client->SetUser($id);
     header("Location:$loc");
    }
   }
  }
 }
 $success = $client->Finalize($success);
}
if($client->exit){
 ser("Something Happened","<a href='".$client->redirect_uri."'>Try Again</a>");
}
if(!$success){
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
