<?
class Open{
 public $uid, $sid, $root;
 public $lg=false;
 public $dbh, $host;
 public $definedVars; /* Variables that are defined */
 public $mUsers = array();
 private $secureKey='private_value';
 private $cache=array();
 
 public function __construct(){
  /* The Document Root */
  $this->root = realpath(str_replace("inc", "", dirname(__FILE__)))."/";
  /* Cookie default values, so that PHP doesn't throw errors if cookies are not in $_COOKIE */
  $_COOKIE['wervsi']=isset($_COOKIE['wervsi']) ? $_COOKIE['wervsi']:"";
  $_COOKIE['curuser']=isset($_COOKIE['curuser']) ? $_COOKIE['curuser']:"";
  
  /* The user id of the current logged in user. */
  $this->uid = $_COOKIE['curuser']=='' ? "Varghese" : $_COOKIE['curuser'];
  /* The secure cookie value when decrypted */
  $this->sid = $_COOKIE['wervsi']=='' ? "Chinnan" : $this->decrypter($_COOKIE['wervsi']); /* 28 Nov 2013 */
  /* A boolean of logged in status */
  $this->lg  = $this->uid==$this->sid ? true:false;
  
  /* We make the Database configuration into an array */
  $database=unserialize(DATABASE);
  /* We make the PDO object */
  $this->dbh = new PDO("mysql:dbname=".$database["name"].";host=".$database["host"].";port=".$database["port"], $database["user"], $database["pass"]);
  
  /* The hostname */
  $this->host="http://".$_SERVER['HTTP_HOST'];/* currently no SSL for Open */
  
  /* Actions to do if the user is logged in */
  if($this->lg){
   $sql=$this->dbh->prepare("SELECT `id` FROM `users` WHERE `id`=?");
   $sql->execute(array($this->uid));
   if($sql->rowCount()==0){
    $this->lg=false;
   }
  }
  return true;
 }
 
 /* Include files from Open root dir */
 /* Avoid using this function as you can and use include_once or require with $docRoot */
 public function inc($file){
  /* Re-define the already defined variables 
   * Because in classes' function variables defined outside classes are not available.
   * Hence, we get the defined variables from outside and insert them into $OP->definedVars as an array
   * Then the variables in the array is redefined. See php.net manual on extract() for more information
   * This is a time consuming process. So, use this function carefully at really necessary places.
  */
  extract($this->definedVars);
  /* This function supports including of multiple files at the same time */
  /* Only a file needs to be included */
  include $docRoot.$file;
  return true;
 }
 
 /* Make the <head> tag. All params are not required */
 /* Accepts title, js & css files that are separated by a comma (,) */
 public function head($title="", $js="", $css=""){
  if($title!=""){
   echo "<title>$title | Open - An Open Source Social Network</title>";
  }else{
   echo "<title>Open - An Open Source Social Network</title>";
  }
  /* Make the Favicon */
  echo "<link rel='icon' href='" . HOST . "/source/cdn/img/favicon.ico' />";
  /* The 'ubuntu' font */
  echo "<link async='async' href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>";
  /* The Open CSS files */
  $css = $css!="" ? "main,$css":"main";
  echo "<link async='async' type='text/css' rel='stylesheet' href='" . HOST . "/source/cdn/css/get?f=".$css."' />";
  /* The Open JS files */
  /* First, stats tracker */
  if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!="/me/ResetPassword"){
   /* It's directly placed in a <script> tag */
   echo "<script>".file_get_contents($this->root."/source/cdn/js/stats.js")."</script>";
  }
  /* Then, jQuery*/
  echo "<script src='" . HOST . "/source/cdn/js/jquery.js'></script>";
  /* Then the other JS files that are mostly jQuery - Thank you John Resig */
  $js = $js=="" ? "main":"main,$js";
  echo "<script async='async' src='" . HOST . "/source/cdn/js/get?f=".$js."'></script>";
 }
 
 /* Encrypts a string that is decryptable */
 public function encrypter($value){
  if($value=='') return false;
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->secureKey, $value, MCRYPT_MODE_ECB, $iv);
  return urlencode(trim(base64_encode($crypttext)));
 }
 
 /* Decrypt hashes encrypted with $OP->encrypter() */
 public function decrypter($value){
  $value=urldecode($value);
  if(!$value || $value==null || $value=='' || base64_encode(base64_decode($value)) != $value){
   return $value;
  }else{
   $crypttext = base64_decode($value);
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->secureKey, $crypttext, MCRYPT_MODE_ECB, $iv);
   return trim($decrypttext);
  }
 }
 
 /* Generate a random string */
 public function randStr($length){
  $str="";
  $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $size=strlen($chars);
  for($i=0;$i<$length;$i++){
   $str.=$chars[rand(0, $size-1)];
  }
  return $str;
 }
 
 /* Get user options */
 public function get($key, $userID="", $j=true){
  if($userID == ""){
   $userID = $this->uid;
  }
  if(isset($this->cache['uData'][$userID])){
   	$data=$this->cache['uData'][$userID];
  }else{
   	$sql=$this->dbh->prepare("SELECT * FROM `users` WHERE `id`=:id");
   	$sql->bindValue(":id", $userID, PDO::PARAM_INT);
   	$sql->execute();
   	if($sql->rowCount()!=0){
    		$data = $sql->fetch(PDO::FETCH_ASSOC);
    		$newJSON = json_decode($data['udata'], true);
    		$newJSON = is_array($newJSON) ? $newJSON : array();
    		$newJSON['ploc']  = HOST . "/" . $userID;
    		$data['udata'] = json_encode($newJSON);
    		$this->cache['uData'][$userID]=$data;
   	}
  }
  if(isset($data)){
  		if($key == 'img'){
   		$data = json_decode($data['udata'], true);
   		$data = isset($data["img"]) ? $this->format($data["img"]):"";
   		$data = $data=='' ? HOST . "/source/cdn/img/profile_pics/om":$data;
   		return $data;
  		}elseif($key == 'plink'){
   		return HOST."/$userID";
  		}elseif($key == "status"){
   		$data = $data['seen'];
   		if($data < date("Y-m-d H:i:s",strtotime('-20 seconds', time()))){
    			return "off";
   		}else{
    			return "on";
   		}
  		}elseif($key == "avatar"){
   		$img = get("img", $userID);
   		if(preg_match("/profile\_pics\/om/", $img) || $img==""){
    			$img = HOST . "/source/cdn/img/profile_pics/om";
   		}elseif(!preg_match("/imgur/", $img) && !preg_match("/akamaihd/", $img) && !preg_match("/google/", $img) && $img!=""){
    			$img = "$img/small.png";
   		}
   		return $img;
  		}elseif($key == "fname"){
   		$data = $this->format($data["name"]);
   		$data = explode(" ", $data);
   		return $data[0];
  		}elseif($j === true){
   		$data = json_decode($data['udata'], true);
   		if(isset($data[$key])){
    			$data = is_array($data[$key]) ? $data[$key] : $this->format($data[$key]);
   		}else{
    			$data = "";
   		}
   		return $data;
  		}else{
   		return isset($data[$key]) ? $this->format($data[$key]):"";
  		}
  }
 }
 
 /* Function to save data of current user */
 public function save($key, $val=""){
  $sql=$this->dbh->prepare("SELECT `udata` FROM `users` WHERE `id`=?");
  $sql->execute(array($this->uid));
  $data=$sql->fetch();
  if($key=="seen"){
   $val=date("Y-m-d H:i:s", time());
   $sql=$this->dbh->prepare("UPDATE `users` SET `seen`=? WHERE `id`=?");
   $sql->execute(array($val, $this->uid));
  }else{
   $arr=json_decode($data['udata'], true);
   $arr[$key]=$val;
   $sql=$this->dbh->prepare("UPDATE `users` SET `udata`=? WHERE `id`=?");
   $sql->execute(array(json_encode($arr), $this->uid));
  }
 }
 
 /* A redirect function that support HTTP status code for redirection 
  * 302 - Moved Temporarily
 */
 public function redirect($url, $status=302){
  		header("Location: $url", true, $status);
  		exit;
  		return true;
 }
 
 /* Function to show error messages. If no values are given, error 400 is shown */
 public function ser($title="", $description="", $type="html"){
  if($type=="html"){
   if($title==''){
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    $this->inc('inc/all_errors_page.php');
   }else{
    $html="<h2 style='color:red;font-family:ubuntu;'>$title</h2>";
    if($description!=''){
     $html.="<span style='color:red;font-family:ubuntu;'>$description</span>";
    }
    echo $html;
   }
  }elseif($type=="json"){
   header('Content-type: text/html');
   if($title!=''){
    $json='{"error":"1", "msg":"'.$title.'"}';
   }else{
    $json='{"error":"1", "msg":"There was an error, Check If something is wrong."}';
   }
   echo $json;
  }
  exit; /* We don't want other code to run */
 }
 
 public function sss($title, $description){ /* To show success messages */
  if($title==''){
   $html="<h2 style='color:green;font-family:ubuntu;'>Operation Success</h2>";
  }else{
   $html="<h2 style='color:green;font-family:ubuntu;'>$title</h2>";
  }
  if($description!=''){
   $html.="<span style='color:green;font-family:ubuntu;'>$description</span>";
  }
  echo $html;
  /* No exit here because it's a success message */
 }
 
 public function init($force=false){
  global $_SERVER;
  /* Pages that doesn't required logging in */
  $no_login_required_ps = array(
   "/index",
   "/"
  );
  /* Get the Site Base. Useful when Open is in a sub directory */
  $siteBase = str_replace($_SERVER['DOCUMENT_ROOT'], "", $this->root);
  if($siteBase != "/"){
  		/* Open is in a subdirectory, so make that folder the "/" of Open to make the request URI from //example.com/subdirectory/myfile to /myfile */
  		$requestPage = str_replace($siteBase, "", $_SERVER['REQUEST_URI']);
  }else{
  		/* Open is in the root dir, so directly use REQUEST_URI of PHP */
  		$requestPage = $_SERVER['REQUEST_URI'];
  }
  if(!$this->lg || $force===true){
   if(array_search($requestPage, $no_login_required_ps) == false){
    $this->redirect(HOST . "/login?c=" . $requestPage);
    exit;
   }
  }elseif(array_search($requestPage, $no_login_required_ps) !== false){
   $this->redirect(HOST . "/home");
   exit;
  }
 }
 
 /* Format Filtering (Formatting) */
 
 /* For making clean URLs */
 private function goodURL($m){
  $ots="";
  $u=str_replace("\n", "", str_replace("\s", "", $m[0]));
  if($m[2]=='http://' || $m[2]=='https://' || $m[2]=='www'){
   $t=$m[1];
  }else{
   $t=$m[2];
  }
  if(preg_match("/\n/", $t)){
   $t=str_replace("\n", "", $t);
   $ots="\n";
  }
  if($m[2]=='www'){
   $u="http://$u";
  }
  return '<a href="'.$u.'">'.$t.'</a>'.$ots;
 }
 
 /* Makes @1 into <a href='//open.subinsb.com/1'>@SubinSiby</a> */
 public function smention($s, $t){
  $userid=$t[1];
  $nxs=strpos($s, "@$userid");
  $nxs=strlen("@$userid") + $nxs;
  $nxs=substr($s, $nxs, 1);
  $sql=$this->dbh->prepare("SELECT `name` FROM `users` WHERE `id`=?");
  $sql->execute(array($userid));
  if($sql->rowCount()==0){
   return "@$userid".$nxs;
  }else{
   while($r=$sql->fetch()){
    $name=$r['name'];
   }
   $html="<a href='" . HOST . "/$userid'>@$name</a>".$nxs;
   $this->mUsers[$userid]=1;
   return $html;
  }
 }
 
 /* Make notifications for mentioned users */
 public function mentionNotify($pid, $type="post"){
  $this->inc("inc/notify.php");
  /* Short for mentioned users - mUsers */
  $mUsers=$this->mUsers;
  if(count($mUsers)!=0){  
   foreach($mUsers as $userId=>$notNeeded){
    if($userId!=$who){
     notify("mention", $type, $pid, $userId, $who);
    }
   }
  }
 }
 
 /* A formatting function to filter from HTML string (prevent XSS) and also advanced filtering (@mentions etc..) */
 public function format($string, $advanced=false){
  $string = htmlspecialchars($string);
  if($advanced === true){
   	$string = preg_replace("/\*\*(.*?)\*\*/", '<b>$1</b>', $string);
   	$string = preg_replace("/\*\/(.*?)\/\*/", '<i>$1</i>', $string);
   	$string = preg_replace_callback('@((www|http://|https://)(.*?)(\s|\z|\n)+)@', array($this, 'goodURL'), $string);
   	$string = preg_replace('@(\#[^ ]+)@', '<a href="http://open.subinsb.com/search?q=\1">\1</a>', $string);
   	$string = str_replace("http://open.subinsb.com/search?q=#", "http://open.subinsb.com/search?q=%23", $string);
   	$that   = $this;
   	$string = preg_replace_callback("/\@(.*?)(\s|\z|[^0-9])/", function($text) use ($string, $that){
    		return $that->smention($string, $text);
   	}, $string);
  }
  return $string;
 }
 
 /* Converts long multiple lines string in to a single line string. Short for Rendering Filter */
 /* Very useful for including in JavaScript variables */
 public function rendFilt($string){
  $newString = str_replace("\r", "", $string);
  $newString = str_replace('"', "'", $newString);
  $newString = str_replace("/",'"+"/"+"', $newString);
  $newString = str_replace("\n", "<br/>", $newString);
  return $newString;
 }
 
 /* End Fomratting Functions */
 /* Home, Feed Functions */
 
 /* Get all the likes of the current logged in user */
 private function getLikes($type="post"){
  $k=array();
  if(isset($this->cache["all".$type."Likes"])){
   $k=$this->cache["all".$type."Likes"];
   $e=null;
  }
  if($type=="post" && !isset($e)){
   $sql=$this->dbh->prepare("SELECT `pid` FROM `likes` WHERE uid=?");
   $sql->execute(array($this->uid));
   while($r=$sql->fetch()){
    $k[]=$r['pid'];
   }
  }elseif($type=="cmt" && !isset($e)){
   $sql=$this->dbh->prepare("SELECT `cid` FROM `clikes` WHERE uid=?");
   $sql->execute(array($this->uid));
   while($r=$sql->fetch()){
    $k[]=$r['cid'];
   }
  }
  if(!isset($e)){
   $this->cache["all".$type."Likes"]=$k;
  }
  return $k;
 }
 
 /* Give the post/comment ID and a boolean whether if the user liked it or not is given */
 public function didLike($id, $type){
  if($type=="post"){
   $all=$this->getLikes("post");
  }else{
   $all=$this->getLikes("cmt");
  }
  return array_search($id, $all)===false ? false:true;
 }
 
 /* Prints the HTML of Follow button when the User id is given */
 public function followButton($uid){
  if($uid==$this->uid || !$this->lg){
   return false;
  }else{
   $sql=$this->dbh->prepare("SELECT `uid` FROM `conn` WHERE `fid`=? and `uid`=?");
   $sql->execute(array($uid, $this->uid));
   if($sql->rowCount()==0){
    return '<button id="'.$uid.'" class="follow"><span hide>Follow</span>+</button>';
   }else{
    return '<button id="'.$uid.'" class="unfollow"><span hide>UnFollow</span>-</button>';
   }
  }
 }
 
 /* End Feed/Home */
 /* Other functions */
 
 /* For sending mails */
 
 /* 5 accounts are currently used for sending mails
  * by shift. If one account is used before,
  * the next account is used now.
  * One Mailgun account and 4 Microsoft Outlook acccounts
 */
 public function sendEMail($mail, $subject, $content){
  /* Prepare the HTML message */
  $html = '<div style="width:100%;margin:0px;background:#EEE;background:-webkit-linear-gradient(#CCC,#EEE);background:-moz-linear-gradient(#CCC,#EEE);padding:2px;height:100px;"><h1><a href="http://open.subinsb.com"><img style="margin-left:40px;float:left;" src="http://open.subinsb.com/source/cdn/img/logo.png"></a></h1><div style="float:right;margin-right:40px;font-size:20px;margin-top:20px"><a href="http://open.subinsb.com/me">Manage Account</a>&nbsp;&nbsp;&nbsp;<a href="http://open.subinsb.com/me/ResetPassword">Forgot password ?</a></div></div><h2>'.$subject.'</h2><div style="margin-left: 10px;padding: 5px 10px;margin-right:10px">'.$content.'</div><p>Report Bugs, Problems, Suggestions & Feedback @ <a href="https://github.com/subins2000/open/issues">GitHub</a> Or Send Feedback Via HashTag <a href="http://open.subinsb.com/search?q=%23feedback">feedback</a><br/><a href="http://open.subinsb.com/me/Notify">Manage Mail Notifications</a></p>';
  
  /* Append Company name to the subject */
  $subject     .= " - Open";
  
  /* We keep a status file for knowing which Mail Account was used for sending the previous email */
  $statusFile  = $this->root."/inc/lastused.txt";
  /* $nowAccount means that the account that should be currently used to send the email */
  $nowAccount = file_get_contents($statusFile);
  
  if($nowAccount=="5" || $nowAccount=="0"){
   /* This is the Mailgun account */
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   curl_setopt($ch, CURLOPT_USERPWD, 'private_value');
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
   curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/open.subinsb.com/messages');
   curl_setopt($ch, CURLOPT_POSTFIELDS, array(
     "from"    => 'Open <noreply@open.subinsb.com>',
     "to"      => $mail,
     "subject" => $subject,
     "html"    => $html
    )
   );
   $result = curl_exec($ch);
   curl_close($ch);
   $newStatus=$nowAccount=="5" ? 0 : $nowAccount+1;
  }else{
   $mailAccounts = array(
    1 => array("noreply@open.subinsb.com", "private_value"),
    2 => array("noreply2@open.subinsb.com", "private_value"),
    3 => array("noreply3@open.subinsb.com", "private_value"),
    4 => array("noreply4@open.subinsb.com", "private_value")
   );
   $user  = $mailAccounts[$nowAccount][0];
   $pass  = $mailAccounts[$nowAccount][1];
   $phpMailer = new PHPMailer();
   $phpMailer->IsSMTP();
   $phpMailer->CharSet     = 'UTF-8';
   $phpMailer->Host        = "smtp.live.com";
   $phpMailer->SMTPAuth    = true;
   $phpMailer->Port        = 587;
   $phpMailer->Username    = $user;
   $phpMailer->Password    = $pass;
   $phpMailer->SMTPSecure  = 'tls';
   $phpMailer->From        = 'noreply@open.subinsb.com';
   $phpMailer->FromName    = 'Open Auto Mail';
   $phpMailer->isHTML(true);
   $phpMailer->Subject     = $subject;
   $phpMailer->SMTPDebug   = false;
   $phpMailer->Debugoutput = 'echo';
   $phpMailer->Body        = $html;
   $phpMailer->addAddress($mail);
   $result=$phpMailer->send();
   
   /* $lastAccount+1 - the next account that should be used */
   $newStatus=$nowAccount+1;
  }
  
  /* We replace with the new status (the last account used) */
  if(isset($newStatus)){
   file_put_contents($statusFile, $newStatus);
  }
  return $result;
 }
}
?>