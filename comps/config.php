<?
if($_GET['show_errors']!=""){
 ini_set("display_errors","on");
}
$dbname=getenv('OPENSHIFT_GEAR_NAME');
$host=getenv('OPENSHIFT_MYSQL_DB_HOST');
$port=getenv('OPENSHIFT_MYSQL_DB_PORT');
$usr=getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$pass=getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
$db=new PDO("mysql:dbname=$dbname;host=$host;port=".$port, $usr, $pass);
if(!function_exists("encrypter")){
 function encrypter($value){
  if($value==''){return false;}
  $key = 'akey';
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $value, MCRYPT_MODE_ECB, $iv);
  return urlencode(trim(base64_encode($crypttext)));
 }
}
if(!function_exists("decrypter")){
 function decrypter($value){
  $value=urldecode($value);
  if(!$value || $value==null || $value=='' || base64_encode(base64_decode($value)) != $value){
   return $value;
  }else{
   $key = 'akey';
   $crypttext = base64_decode($value);
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
   return trim($decrypttext);
  }
 }
}
$who=$_COOKIE['curuser']=='' ? "Varghese":$_COOKIE['curuser'];
$whod=$_COOKIE['wervsi']=='' ? "Chinnan":decrypter($_COOKIE['wervsi']);/*28 Nov 2013*/
$lg=$whod==$who ? true:false;
if($lg){
 $sql=$db->prepare("SELECT id FROM users WHERE id=?");
 $sql->execute(array($who));
 if($sql->rowCount()==0){
  $lg=false;
 }
}
if(!function_exists("ser")){
 function ser($t,$d){
  if($t==''){
   header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
   include('all_errors_page.php');
  }else{
   $er="<h2 style='color:red;font-family:ubuntu;'>$t</h2>";
   if($d!=''){
    $er.="<span style='color:red;font-family:ubuntu;'>$d</span>";
   }
  }
  echo $er;
  exit;
 }
}
if(!function_exists("sss")){
 function sss($t,$d){
  if($t==''){
   $s="<h2 style='color:green;font-family:ubuntu;'>Operation Success</h2>";
  }else{
   $s="<h2 style='color:green;font-family:ubuntu;'>$t</h2>";
  }
  if($d!=''){
   $s.="<span style='color:green;font-family:ubuntu;'>$d</span>";
  }else{
   $s.="<span style='color:green;font-family:ubuntu;'>$d</span>";
  }
  echo $s;
 }
}
if(!function_exists("jer")){
 function jer($t){
  header('Content-type: text/html');
  if($t!=''){
   $er='{"error":"1","msg":"'.$t.'"}';
  }else{
   $er='{"error":"1","msg":"There was an error, Check If something is wrong."}';
  }
  echo $er;
  exit;
 }
}
if(!function_exists("ch")){
 function ch($t=false){
  global $lg;
  global $_SERVER;
  $no_login_required_ps=array("/index","/");
  if(!$lg || $t===true){
   if(array_search($_SERVER['REQUEST_URI'],$no_login_required_ps)==false){
    header("Location: http://open.subinsb.com/login?c=//open.subinsb.com".$_SERVER['REQUEST_URI']);
    exit;
   }
  }elseif(array_search($_SERVER['REQUEST_URI'],$no_login_required_ps)!=false){
   header("Location: //open.subinsb.com/home");
   exit;
  }
 }
}
if(!function_exists("ch_url")){
 function ch_url($m){
  $u=urlencode($m[0]);
  $t=$m[2]=='http://' ? $m[1]:$m[2];
  return'<a target="_blank" href="//open.subinsb.com/url?url='.$u.'">'.$t.'</a>';
 }
}
if(!function_exists("smention")){
 $mUsers=array();
 function smention($s,$t){
  $userid=$t[1];
  $nxs=strpos($s,"@$userid");
  $nxs=strlen("@$userid")+$nxs;
  $nxs=substr($s,$nxs,1);
  global$db, $mUsers;
  $sql=$db->prepare("SELECT username,name FROM users WHERE id=?");
  $sql->execute(array($userid));
  if($sql->rowCount()==0){
   return"@$userid".$nxs;
  }else{
   while($r=$sql->fetch()){
    $name=$r['name'];
    $mail=$r['username'];
   }
   $html="<a href='//open.subinsb.com/$userid'>@$name</a>".$nxs;
   $mUsers[$userid]=1;
   return$html;
  }
 }
}
if(!function_exists("filt")){
 function filt($s,$r=false){
  $s=htmlspecialchars($s);
  if($r==true){
   $s=preg_replace("/\*\*(.*?)\*\*/",'<b>$1</b>',$s);
   $s=preg_replace("/\*\/(.*?)\/\*/",'<i>$1</i>',$s);
   $s=preg_replace_callback("/\(\[(.*?)\](.*?)\)/","ch_url",$s);
   $s=preg_replace_callback('@((www|http://|https://)[^ ]+)@',"ch_url",$s);
   $s=preg_replace('@(\#[^ ]+)@','<a href="//open.subinsb.com/search?q=\1">\1</a>',$s);
   $s=str_replace("//open.subinsb.com/search?q=#","//open.subinsb.com/search?q=%23",$s);
   $s=preg_replace_callback("/\@(.*?)(\s|\z|[^0-9])/", function($t) use ($s){return smention($s,$t);},$s);
  }
  return $s;
 }
}
if(!function_exists("get")){
 $load_cache=array();
 function get($k,$u,$j=true){
  global$db;global$who;global$load_cache;
  if(is_null($u)){$u=$who;}
  if(!array_key_exists($u,$load_cache)){
   $sql=$db->prepare("SELECT * FROM users WHERE id=?");
   $sql->execute(array($u));
   $data=$sql->fetch();
   $uvno=json_decode($data['udata'],true);
   $uvno['ploc']="//open.subinsb.com/".$u;
   $data['udata']=json_encode($uvno);
   $load_cache[$u]=$data;
  }else{
   $data=$load_cache[$u];
  }
  if($k=='img'){
   $data=json_decode($data['udata'],true);
   $data=filt($data["img"]);
   $data=$data=='' ? "//open.subinsb.com/img/profile_pics/om":$data;
   return$data;
  }elseif($k=='plink'){
   return"http://open.subinsb.com/$u";
  }elseif($k=="status"){
   $data=$data['seen'];
   if($data < date("Y-m-d H:i:s",strtotime('-25 seconds', time()))){
    return "off";
   }else{
    return "on";
   }
  }elseif($k=="avatar"){
   $img=get("img",$u);
   if(preg_match("/profile\_pics\/om/",$img) || $img==""){
    $img="http://open.subinsb.com/img/profile_pics/om";
   }elseif(!preg_match("/imgur/",$img) && !preg_match("/akamaihd/",$img) && !preg_match("/google/",$img) && $img!=""){
    $img="http://open.subinsb.com/data/{$u}/img/avatar";
   }
   return $img;
  }elseif($j==true){
   $data=json_decode($data['udata'],true);
   $data=filt($data[$k]);
   return$data;
  }elseif($k=="fname"){
   $data=filt($data["name"]);
   $data=explode(" ",$data);
   return $data[0];
  }else{
   return filt($data[$k]);
  }
 }
}
if(!function_exists("save")){
 function save($key,$val=""){
  global$db;global$who;
  $sql=$db->prepare("SELECT udata FROM users WHERE id=?");
  $sql->execute(array($who));
  $data=$sql->fetch();
  if($key=="seen"){
   $val=date("Y-m-d H:i:s",time());
   $sql=$db->prepare("UPDATE users SET seen=? WHERE id=?");
   $sql->execute(array($val,$who));
  }else{
   $arr=json_decode($data['udata'],true);
   $arr[$key]=$val;
   $sql=$db->prepare("UPDATE users SET udata=? WHERE id=?");
   $sql->execute(array(json_encode($arr),$who));
  }
 }
}
if(!isset($al_coll_dt)){
 $al_coll_dt=false;
}
if($lg && $al_coll_dt==false){
 $al_coll_dt=true;
 $uimg=get("img");
 $uname=get("name",null,false);
 save("seen");
}
/*Global Variables*/
$_P=count($_POST)>0 ? true:false; 
$sroot=$_SERVER['DOCUMENT_ROOT'];
/*Other Functions*/
if(!function_exists("foll")){
 function foll($id){
  global$who;global$db;global$lg;
  if($id==$who || !$lg){return false;}
  $sql=$db->prepare("SELECT uid FROM conn WHERE fid=? and uid=?");
  $sql->execute(array($id,$who));
  if($sql->rowCount()==0){
   return'<button id="'.$id.'" class="follow"><span hide>Follow</span>+</button>';
  }else{
   return'<button id="'.$id.'" class="unfollow"><span hide>UnFollow</span>-</button>';
  }
 }
}
if(!function_exists("send_mail")){
 function send_mail($mail,$subject,$msg) {
  $msg='<div style="width:100%;margin:0px;background:#EEE;background:-webkit-linear-gradient(#CCC,#EEE);background:-moz-linear-gradient(#CCC,#EEE);padding:2px;height:100px;"><h1><a href="http://open.subinsb.com"><img style="margin-left:40px;float:left;" src="http://open.subinsb.com/img/logo.png"></a></h1><div style="float:right;margin-right:40px;font-size:20px;margin-top:20px"><a href="http://open.subinsb.com/me">Manage Account</a>&nbsp;&nbsp;&nbsp;<a href="http://open.subinsb.com/me/ResetPassword">Forgot password ?</a></div></div><h2>'.$subject.'</h2><div style="margin-left: 10px;border: 3px solid black;padding: 5px 10px;border-radius:5px;margin-right:10px">'.$msg.'</div><br/>Report Bugs, Problems, Suggestions & Feedback @ <a href="https://github.com/subins2000/open/issues">GitHub</a> Or Send Feedback Via HashTag <a href="http://open.subinsb.com/search?q=%23feedback">feedback</a>';
  $subject.=" - Open";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, 'mailgun_key');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/open.subinsb.com/messages');
  curl_setopt($ch, CURLOPT_POSTFIELDS, array(
   'from' => 'Open <noreply@open.subinsb.com>',
   'to' => $mail,
   'subject' => $subject,
   'html' => $msg));
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
 }
}
include("notify.php");
if(!function_exists("sm_notify")){
 function sm_notify($pid){
  global $mUsers, $who;
  if(count($mUsers)!=0){  
   foreach($mUsers as $k=>$v){
    if($k!=$who){
     notify("mention","post",$pid,$k,$who);
    }
   }
  }
 }
}
?>
