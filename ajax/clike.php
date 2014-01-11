<?
include("config.php");
ch();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$db->prepare("SELECT id FROM cmt WHERE id=?");
 $sql->execute(array($id));
 if($sql->rowCount()!=0){
  $sql=$db->prepare("SELECT * FROM clikes WHERE uid=? AND cid=?");
  $sql->execute(array($who,$id));
  if($sql->rowCount()==0){
   $sql=$db->prepare("INSERT INTO clikes (uid,cid,liked) VALUES (?,?,NOW())");
   $sql->execute(array($who,$id));
   $sql=$db->prepare("UPDATE cmt SET likes=likes+1 WHERE id=?");
   $sql->execute(array($id));
  }else{
   $sql=$db->prepare("DELETE FROM clikes WHERE uid=? AND cid=?");
   $sql->execute(array($who,$id));
   $sql=$db->prepare("UPDATE cmt SET likes=likes-1 WHERE id=?");
   $sql->execute(array($id));
  }
 }
}else{
 ser();
}
?>
