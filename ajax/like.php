<?
include("config.php");
ch();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$db->prepare("SELECT id FROM posts WHERE id=?");
 $sql->execute(array($id));
 if($sql->rowCount()!=0){
  $sql=$db->prepare("SELECT * FROM likes WHERE uid=? AND pid=?");
  $sql->execute(array($who,$id));
  if($sql->rowCount()==0){
   $sql=$db->prepare("INSERT INTO likes (uid,pid,liked) VALUES (?,?,NOW())");
   $sql->execute(array($who,$id));
   $sql=$db->prepare("UPDATE posts SET likes=likes+1 WHERE id=?");
   $sql->execute(array($id));
  }else{
   $sql=$db->prepare("DELETE FROM likes WHERE uid=? AND pid=?");
   $sql->execute(array($who,$id));
   $sql=$db->prepare("UPDATE posts SET likes=likes-1 WHERE id=?");
   $sql->execute(array($id));
  }
 }
}else{
 ser();
}
?>
