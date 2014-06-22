<?

$OP->init();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$OP->dbh->prepare("SELECT id FROM users WHERE id=?");
 $sql->execute(array($id));
 if($sql->rowCount()!=0){
  $sql=$OP->dbh->prepare("SELECT * FROM conn WHERE uid=? AND fid=?");
  $sql->execute(array($who, $id));
  if($sql->rowCount()==0){
   $sql=$OP->dbh->prepare("INSERT INTO conn (uid,fid,since) VALUES (?,?,NOW())");
   $sql->execute(array($who, $id));
   $OP->inc("inc/notify.php");
   notify("follow",0,0, $id, $who);
?>
$("#<?echo$id;?>.follow").removeClass("follow").addClass("unfollow").html("<span hide>UnFollow</span>-");
<?
  }else{
   $sql=$OP->dbh->prepare("DELETE FROM conn WHERE uid=? AND fid=?");
   $sql->execute(array($who, $id));
?>
$("#<?echo$id;?>.unfollow").removeClass("unfollow").addClass("follow").html("<span hide>Follow</span>+");
<?
  }
 }else{
  $OP->ser();
 }
}else{
 $OP->ser();
}
?>
