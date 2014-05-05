<?
include("config.php");
include("../inc/cmt_rend.php");
ch();
$id=$_POST['id'];
$msg=$_POST['cmt'];
if($_P && is_numeric($id)){
 if(!preg_match("/[^\s]/", $msg)){
  jer("Comment Can't be blank");
 }
 $sql=$db->prepare("SELECT uid FROM posts WHERE id=?");
 $sql->execute(array($id));
 $owner=$sql->fetchColumn();
 if($sql->rowCount()!=0){
  filt($msg, true); /* Just For @mention notifications */
  $sql=$db->prepare("INSERT INTO cmt (uid, pid, cmt, posted) VALUES(:uid, :id, :msg, NOW());UPDATE posts SET cmts=cmts+1 WHERE id=:id");
  $sql->execute(array(
   ":uid" => $who,
   ":id"  => $id,
   ":msg" => $msg
  ));
  notify("comment", $msg, $id, $owner, $who);/* We should notify the owner of post */
  sm_notify($id, "comment");
  if($_POST['clod']=='mom'){
   $_POST['all']=1;
  }
  $ht=rendFilt(show_cmt($id));
?>
$("#<?echo$id;?>.comments").replaceWith("<?echo$ht;?>");
$("#<?echo$id;?>.comments").show();
$("#<?echo$id;?>.ck.count").text(parseFloat($("#<?echo$id;?>.ck.count").text())+1);
<?
 }else{
  jer("I can't find the post you wished to comment on.");
 }
}else{
 ser();
}
?>
