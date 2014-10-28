<?php
require_once "$docRoot/inc/render.php";
$LS->init();

if($_P){
	$id  = $_POST['id'];
	$msg = $_POST['cmt'];
	if(!preg_match("/[^\s]/", $msg)){
		ser("Comment Can't be blank", "", "json");
	}
	if(!is_numeric($id)){
		ser("Invalid Request", "", "json");
	}
	$sql   = $OP->dbh->prepare("SELECT `uid` FROM `posts` WHERE `id`=?");
	$sql->execute(array($id));
	$owner = $sql->fetchColumn();
 
	if($sql->rowCount() != 0){
		$OP->format($msg, true); /* Just For @mention notifications */
		$sql = $OP->dbh->prepare("INSERT INTO `comments` (`uid`, `pid`, `comment`, `time`) VALUES(:uid, :id, :msg, NOW());
			UPDATE `posts` SET `comments` = `comments` + 1 WHERE `id`=:id");
		$sql->execute(array(
			":uid" => curUser,
			":id"  => $id,
			":msg" => $msg
		));
		$OP->mentionNotify($id, "comment");
  
		$OP->notify("comment", $msg, $id, $owner, curUser);/* We should notify the owner of post */
  
		/* Show all comments or not */
		if($_POST['clod'] == 'mom'){
			$_POST['all'] = 1;
		}
		$html = $OP->rendFilt(Render::comment($id));
?>
$("#<?php echo$id;?>.comments").replaceWith("<?php echo $html;?>");
$("#<?php echo$id;?>.comments").show();
$("#<?php echo$id;?>.ck.count").text(parseFloat($("#<?php echo $id;?>.ck.count").text())+1);
<?php
	}else{
		$OP->ser("I can't find the post you wished to comment on.", "", "json");
	}
}else{
	$OP->ser();
}
?>