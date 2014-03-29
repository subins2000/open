<?
class Open{
 public $uid, $sid;
 public $lg=false;
 private $secureKey='akey';
 function __construct(){
  global $db;
  $_COOKIE['wervsi']=isset($_COOKIE['wervsi']) ? $_COOKIE['wervsi']:"";
  $_COOKIE['curuser']=isset($_COOKIE['curuser']) ? $_COOKIE['curuser']:"";
  $this->uid = $_COOKIE['curuser']=='' ? "Varghese" : $_COOKIE['curuser'];
  $this->sid = $_COOKIE['wervsi']=='' ? "Chinnan" : $this->decrypter($_COOKIE['wervsi']);/*28 Nov 2013*/
  $this->lg  = $this->uid==$this->sid ? true:false;
  if($this->lg){
   $sql=$db->prepare("SELECT id FROM users WHERE id=?");
   $sql->execute(array($this->uid));
   if($sql->rowCount()==0){
    $this->lg=false;
   }
  }
  $this->dbh = $db;
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
}
?>
