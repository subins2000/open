<?
include("config.php");
ch();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$db->prepare("SELECT pid FROM cmt WHERE id=? AND uid=?");
 $sql->execute(array($id,$who));
 if($sql->rowCount()==0){
  ser();
 }else{
  while($r=$sql->fetch()){$pid=$r['pid'];}
  $sql=$db->prepare("DELETE FROM cmt WHERE id=? AND uid=?");
  $sql->execute(array($id,$who));
  $sql=$db->prepare("UPDATE posts SET cmts=cmts-1 WHERE id=?");
  $sql->execute(array($pid));
  $sql=$db->prepare("DELETE FROM clikes WHERE cid=?");
  $sql->execute(array($id));
?>
$("#<?echo$id?>.comment").remove();$("#<?echo$pid;?>.ck.count").text(parseFloat($("#<?echo$pid;?>.ck.count").text())-1);
<?
 }
}else{
 ser();
}
?>
