<?
class Open{
 public $uid, $sid;
 public $lg=false;
 public $dbh;
 private $secureKey='akey';
 private $cache=array();
 public function __construct(){
  global $db;
  $_COOKIE['wervsi']=isset($_COOKIE['wervsi']) ? $_COOKIE['wervsi']:"";
  $_COOKIE['curuser']=isset($_COOKIE['curuser']) ? $_COOKIE['curuser']:"";
  $this->uid = $_COOKIE['curuser']=='' ? "Varghese" : $_COOKIE['curuser'];
  $this->sid = $_COOKIE['wervsi']=='' ? "Chinnan" : $this->decrypter($_COOKIE['wervsi']);/*28 Nov 2013*/
  $this->lg  = $this->uid==$this->sid ? true:false;
  $this->dbh = $db;
  if($this->lg){
   $sql=$this->dbh->prepare("SELECT id FROM users WHERE id=?");
   $sql->execute(array($this->uid));
   if($sql->rowCount()==0){
    $this->lg=false;
   }
  }
  return true;
 }
 public function getData($key="", $uid=null){
  if($uid=null) $uid=$this->uid;
  if($key=="" || !$this->lg){
   return false;
  }else{
   $sql=$this->dbh->prepare("SELECT dataValue FROM userData WHERE dataKey=? AND uid=?");
   $sql->execute(array($key, $uid));
   if($sql->rowCount()==0){
    return 0;
   }else{
    return $sql->fetchColumn();
   }
  }
 }
 public function getGroups(){
  $data=$this->getData("groupsMembership");
  if($data==0){
   return array();
  }else{
   return json_decode($data, true);
  }
 }
 public function encrypter($value){
  if($value=='') return false;
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->secureKey, $value, MCRYPT_MODE_ECB, $iv);
  return urlencode(trim(base64_encode($crypttext)));
 }
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
 public function randStr($length){
  $str="";
  $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $size=strlen($chars);
  for($i=0;$i<$length;$i++){
   $str.=$chars[rand(0,$size-1)];
  }
  return $str;
 }
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
 public function didLike($id, $type){
  if($type=="post"){
   $all=$this->getLikes("post");
  }else{
   $all=$this->getLikes("cmt");
  }
  return array_search($id, $all)===false ? false:true;
 }
 public function get($k, $u="", $j=true){
  if($u==""){
   $u=$this->uid;
  }
  if(isset($this->cache['uData'][$u])){
   $data=$this->cache['uData'][$u];
  }else{
   $sql=$this->dbh->prepare("SELECT * FROM `users` WHERE `id`=:id");
   $sql->bindValue(":id", $u, PDO::PARAM_INT);
   $sql->execute();
   if($sql->rowCount()!=0){
    $data=$sql->fetch(PDO::FETCH_ASSOC);
    $uvno=json_decode($data['udata'], true);
    $uvno=is_array($uvno) ? $uvno:array();
    $uvno['ploc']="http://open.subinsb.com/".$u;
    $data['udata']=json_encode($uvno);
    $this->cache['uData'][$u]=$data;
   }
  }
  if($k=='img'){
   $data=json_decode($data['udata'], true);
   $data=isset($data["img"]) ? filt($data["img"]):"";
   $data=$data=='' ? "http://open.subinsb.com/cdn/img/profile_pics/om":$data;
   return $data;
  }elseif($k=='plink'){
   return"http://open.subinsb.com/$u";
  }elseif($k=="status"){
   $data=$data['seen'];
   if($data < date("Y-m-d H:i:s",strtotime('-20 seconds', time()))){
    return "off";
   }else{
    return "on";
   }
  }elseif($k=="avatar"){
   $img=get("img",$u);
   if(preg_match("/profile\_pics\/om/",$img) || $img==""){
    $img="http://open.subinsb.com/cdn/img/profile_pics/om";
   }elseif(!preg_match("/imgur/",$img) && !preg_match("/akamaihd/",$img) && !preg_match("/google/",$img) && $img!=""){
    $img="http://open.subinsb.com/data/{$u}/img/avatar";
   }
   return $img;
  }elseif($k=="fname"){
   $data=filt($data["name"]);
   $data=explode(" ",$data);
   return $data[0];
  }elseif($j==true){
   $data=json_decode($data['udata'], true);
   if(isset($data[$k])){
    $data=is_array($data[$k]) ? $data[$k]:filt($data[$k]);
   }else{
    $data="";
   }
   return $data;
  }else{
   return isset($data[$k]) ? filt($data[$k]):"";
  }
 }
}
?>
