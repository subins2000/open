<?
require_once "$docRoot/inc/cmt_rend.php";
$OP->init();
$id  = $_POST['id'];
$msg = $_POST['cmt'];

if($_P && is_numeric($id)){
 if(!preg_match("/[^\s]/", $msg)){
  	ser("Comment Can't be blank", "", "json");
 }
 $sql   = $OP->dbh->prepare("SELECT `uid` FROM `posts` WHERE `id`=?");
 $sql->execute(array($id));
 $owner = $sql->fetchColumn();
 
 if($sql->rowCount() != 0){
  $OP->format($msg, true); /* Just For @mention notifications */
  $sql = $OP->dbh->prepare("INSERT INTO `comments` (`uid`, `pid`, `comment`, `time`) VALUES(:uid, :id, :msg, NOW());
  UPDATE `posts` SET `comments` = `comments` + 1 WHERE `id`=:id");
  $sql->execute(array(
   	":uid" => $who,
   	":id"  => $id,
   	":msg" => $msg
  ));
  $OP->mentionNotify($id, "comment");
  
  notify("comment", $msg, $id, $owner, $who);/* We should notify the owner of post */
  
  /* Show all comments or not */
  if($_POST['clod'] == 'mom'){
   	$_POST['all'] = 1;
  }
  $html = $OP->rendFilt(show_cmt($id));
?>
$("#<?echo$id;?>.comments").replaceWith("<?echo $html;?>");
$("#<?echo$id;?>.comments").show();
$("#<?echo$id;?>.ck.count").text(parseFloat($("#<?echo $id;?>.ck.count").text())+1);
<?
 }else{
  	$OP->ser("I can't find the post you wished to comment on.", "", "json");
 }
}else{
 $OP->ser();
}
?>