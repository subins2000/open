<?
include("config.php");
ch();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$db->prepare("DELETE FROM posts WHERE id=? AND uid=?");
 $sql->execute(array($id,$who));
 if($sql->rowCount()==0){
  ser();
 }else{
  $sql=$db->prepare("DELETE FROM cmt WHERE pid=?");
  $sql->execute(array($id));
  $sql=$db->prepare("DELETE FROM likes WHERE pid=?");
  $sql->execute(array($id));
?>
$(".post#<?echo$id?>").remove();
<?
 }
}else{
 ser();
}
?>
