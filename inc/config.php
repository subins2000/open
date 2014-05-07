<?
if(isset($_GET['show_errors'])){
 ini_set("display_errors","on");
}
$dbname=getenv('OPENSHIFT_GEAR_NAME');
$host=getenv('OPENSHIFT_MYSQL_DB_HOST');
$port=getenv('OPENSHIFT_MYSQL_DB_PORT');
$usr=getenv('OPENSHIFT_MYSQL_DB_USERNAME');
$pass=getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
$db=new PDO("mysql:dbname=$dbname;host=$host;port=".$port, $usr, $pass);
if(!isset($OP)){
 require "class.open.php";
 $OP=new Open();
}
$lg   = $OP->lg;
$who  = $OP->uid;
$whod = $OP->sid;
if(!function_exists("redirect")){
 function redirect($u, $s){
  header("Location: $u", true, $s);
  exit;
  return true;
 }
}
if(!function_exists("ser")){
 function ser($t="", $d=""){
  if($t==''){
   header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
   include('all_errors_page.php');
  }else{
   $er="<h2 style='color:red;font-family:ubuntu;'>$t</h2>";
   if($d!=''){
    $er.="<span style='color:red;font-family:ubuntu;'>$d</span>";
   }
   echo $er;
  }
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
    redirect("http://open.subinsb.com/login?c=//open.subinsb.com".$_SERVER['REQUEST_URI']);
    exit;
   }
  }elseif(array_search($_SERVER['REQUEST_URI'],$no_login_required_ps)!=false){
   redirect("//open.subinsb.com/home");
   exit;
  }
 }
}
if(!function_exists("ch_url")){
 function ch_url($m){
  $ots="";
  $u=str_replace("\n","",str_replace("\s","",$m[0]));
  if($m[2]=='http://' || $m[2]=='https://' || $m[2]=='www'){
   $t=$m[1];
  }else{
   $t=$m[2];
  }
  if(preg_match("/\n/",$t)){
   $t=str_replace("\n","",$t);
   $ots="\n";
  }
  if($m[2]=='www'){
   $u="http://$u";
  }
  return '<a href="'.$u.'">'.$t.'</a>'.$ots;
 }
}
if(!function_exists("smention")){
 $mUsers=array();
 function smention($s, $t){
  global $db, $mUsers;
  $userid=$t[1];
  $nxs=strpos($s, "@$userid");
  $nxs=strlen("@$userid") + $nxs;
  $nxs=substr($s, $nxs, 1);
  $sql=$db->prepare("SELECT name FROM users WHERE id=?");
  $sql->execute(array($userid));
  if($sql->rowCount()==0){
   return"@$userid".$nxs;
  }else{
   while($r=$sql->fetch()){
    $name=$r['name'];
   }
   $html="<a href='http://open.subinsb.com/$userid'>@$name</a>".$nxs;
   $mUsers[$userid]=1;
   return $html;
  }
 }
}
if(!function_exists("filt")){
 function filt($s, $r=false){
  $s=htmlspecialchars($s);
  if($r==true){
   $s=preg_replace("/\*\*(.*?)\*\*/",'<b>$1</b>',$s);
   $s=preg_replace("/\*\/(.*?)\/\*/",'<i>$1</i>',$s);
   $s=preg_replace_callback('@((www|http://|https://)(.*?)(\s|\z|\n)+)@',"ch_url",$s);
   $s=preg_replace('@(\#[^ ]+)@','<a href="http://open.subinsb.com/search?q=\1">\1</a>',$s);
   $s=str_replace("http://open.subinsb.com/search?q=#","http://open.subinsb.com/search?q=%23",$s);
   $s=preg_replace_callback("/\@(.*?)(\s|\z|[^0-9])/", function($t) use ($s){
    return smention($s, $t);
   },$s);
  }
  return $s;
 }
}
if(!function_exists("get")){
 $load_cache=array();
 function get($k, $u="", $j=true){
  global $OP;
  return $OP->get($k, $u, $j);
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
 $uaimg=get("avatar");
 $uname=get("name",null,false);
 save("seen");
}
/*Global Variables*/
$_P=count($_POST)>0 ? true:false;
$sroot=realpath(dirname(str_replace("inc", "", __FILE__))."/");
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
 include("$sroot/inc/mailer/class.phpmailer.php");
 function send_mail($mail,$subject,$msg) {
  global $sroot;
  $msg='<div style="width:100%;margin:0px;background:#EEE;background:-webkit-linear-gradient(#CCC,#EEE);background:-moz-linear-gradient(#CCC,#EEE);padding:2px;height:100px;"><h1><a href="http://open.subinsb.com"><img style="margin-left:40px;float:left;" src="http://open.subinsb.com/cdn/img/logo.png"></a></h1><div style="float:right;margin-right:40px;font-size:20px;margin-top:20px"><a href="http://open.subinsb.com/me">Manage Account</a>&nbsp;&nbsp;&nbsp;<a href="http://open.subinsb.com/me/ResetPassword">Forgot password ?</a></div></div><h2>'.$subject.'</h2><div style="margin-left: 10px;padding: 5px 10px;margin-right:10px">'.$msg.'</div><p>Report Bugs, Problems, Suggestions & Feedback @ <a href="https://github.com/subins2000/open/issues">GitHub</a> Or Send Feedback Via HashTag <a href="http://open.subinsb.com/search?q=%23feedback">feedback</a><br/><a href="http://open.subinsb.com/me/Notify">Manage Mail Notifications</a></p>';
  $subject.=" - Open";
  $status_file="$sroot/inc/lastused.txt";
  $acc_status=file_get_contents($status_file);
  if($acc_status=="5" || $acc_status=="0"){
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
    'html' => $msg)
   );
   $result = curl_exec($ch);
   curl_close($ch);
   $new_status=$acc_status=="5" ? 0:$acc_status+1;
  }else{
   $macc=array(
    1 => array("noreply@open.subinsb.com", "password"),
    2 => array("noreply2@open.subinsb.com", "password"),
    3 => array("noreply3@open.subinsb.com", "password"),
    4 => array("noreply4@open.subinsb.com", "password")
   );
   $user  = $macc[$acc_status][0];
   $pass  = $macc[$acc_status][1];
   $smail = new PHPMailer();
   $smail->IsSMTP();
   $smail->CharSet    = 'UTF-8';
   $smail->Host       = "smtp.live.com";
   $smail->SMTPAuth   = true;
   $smail->Port       = 587;
   $smail->Username   = $user;
   $smail->Password   = $pass;
   $smail->SMTPSecure = 'tls';
   $smail->From       = 'noreply@open.subinsb.com';
   $smail->FromName   = 'Open Auto Mail';
   $smail->isHTML(true);
   $smail->Subject    = $subject;
   $smail->SMTPDebug  = false;
   $smail->Debugoutput= 'echo';
   $smail->Body       = $msg;
   $smail->addAddress($mail);
   $result=$smail->send();
   $new_status=$acc_status+1;
  }
  if(isset($new_status)){
   file_put_contents($status_file, $new_status);
  }
  return $result;
 }
}
require "notify.php";
if(!function_exists("sm_notify")){
 function sm_notify($pid, $type="post"){
  global $mUsers, $who;
  if(count($mUsers)!=0){  
   foreach($mUsers as $k=>$v){
    if($k!=$who){
     notify("mention", $type, $pid, $k, $who);
    }
   }
  }
 }
}
if(!function_exists("rendFilt")){
 function rendFilt($h){
  $h=str_replace("\n","<br/>",str_replace("/",'"+"/"+"',str_replace('"',"'",str_replace("\r","",$h))));
  return $h;
 }
}
?>