<?
include("../comps/config.php");
$sql=$db->prepare("SELECT * FROM mails ORDER BY id LIMIT 100");
$sql->execute();
if($sql->rowCount()!=0){
 while($r=$sql->fetch()){
  $id=$r['id'];
  $m=$r['email'];
  $sub=$r['sub'];
  $msg=$r['message'];
  if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/',$m)){
   $sql=$db->prepare("DELETE FROM mails WHERE id=?");
   $sql->execute(array($id));
  }else{
   if(send_mail($m, $sub, $msg)){
    $sql=$db->prepare("DELETE FROM mails WHERE id=?");
    $sql->execute(array($id));
   }
  }
 }
}
?>
