<?
$OP->inc("inc/cmt_rend.php");
$OP->init();
$id=$_POST['id'];
$msg=$_POST['cmt'];
if($_P && is_numeric($id)){
 if(!preg_match("/[^\s]/", $msg)){
  ser("Comment Can't be blank", "", "json");
 }
 $sql=$OP->dbh->prepare("SELECT `uid` FROM `posts` WHERE `id`=?");
 $sql->execute(array($id));
 $owner=$sql->fetchColumn();
 if($sql->rowCount()!=0){
  $OP->format($msg, true); /* Just For @mention notifications */
  $sql=$OP->dbh->prepare("INSERT INTO cmt (uid, pid, cmt, posted) VALUES(:uid, :id, :msg, NOW());UPDATE posts SET cmts=cmts+1 WHERE id=:id");
  $sql->execute(array(
   ":uid" => $who,
   ":id"  => $id,
   ":msg" => $msg
  ));
  $OP->mentionNotify($id, "comment");
  notify("comment", $msg, $id, $owner, $who);/* We should notify the owner of post */
  if($_POST['clod']=='mom'){
   $_POST['all']=1;
  }
  $ht=$OP->rendFilt(show_cmt($id));
?>
$("#<?echo$id;?>.comments").replaceWith("<?echo$ht;?>");
$("#<?echo$id;?>.comments").show();
$("#<?echo$id;?>.ck.count").text(parseFloat($("#<?echo$id;?>.ck.count").text())+1);
<?
 }else{
  $OP->ser("I can't find the post you wished to comment on.", "", "json");
 }
}else{
 $OP->ser();
}
?>