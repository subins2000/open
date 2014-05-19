<?
include("../inc/config.php");
//include(getenv("OPENSHIFT_REPO_DIR")."php/inc/config.php");
$sql=$db->prepare("SELECT * FROM `mails` ORDER BY `id` LIMIT 20");
$sql->execute();
if($sql->rowCount()!=0){
 while($r=$sql->fetch()){
  $id      = $r['id'];
  $email   = $r['email'];
  $subject = $r['sub'];
  $msg     = $r['message'];
  if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/',$email)){
   $sql=$db->prepare("DELETE FROM `mails` WHERE `id`=?");
   $sql->execute(array($id));
  }else{
   if(send_mail($email, $subject, $msg)){
    $sql=$db->prepare("DELETE FROM `mails` WHERE `id`=?");
    $sql->execute(array($id));
   }
  }
 }
}
?>