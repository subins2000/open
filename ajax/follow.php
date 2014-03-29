<?
include("config.php");
ch();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$db->prepare("SELECT id FROM users WHERE id=?");
 $sql->execute(array($id));
 if($sql->rowCount()!=0){
  $sql=$db->prepare("SELECT * FROM conn WHERE uid=? AND fid=?");
  $sql->execute(array($who,$id));
  if($sql->rowCount()==0){
   $sql=$db->prepare("INSERT INTO conn (uid,fid,since) VALUES (?,?,NOW())");
   $sql->execute(array($who,$id));
   include("../inc/notify.php");
   notify("follow",0,0,$id,$who);
?>
$("#<?echo$id;?>.follow").removeClass("follow").addClass("unfollow").html("<span hide>UnFollow</span>-");
<?
  }else{
   $sql=$db->prepare("DELETE FROM conn WHERE uid=? AND fid=?");
   $sql->execute(array($who,$id));
?>
$("#<?echo$id;?>.unfollow").removeClass("unfollow").addClass("follow").html("<span hide>Follow</span>+");
<?
  }
 }else{
  ser();
 }
}else{
 ser();
}
?>
