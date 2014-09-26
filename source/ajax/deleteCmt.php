<?php
$LS->init();
$id = $_POST['id'];
if($_P && is_numeric($id)){
 	$sql=$OP->dbh->prepare("SELECT `pid` FROM `comments` WHERE `id`=? AND `uid`=?");
 	$sql->execute(array($id, $who));
 	if($sql->rowCount()==0){
  		$OP->ser();
 	}else{
  		$pid=$sql->fetchColumn();
  		$sql=$OP->dbh->prepare("DELETE FROM `comments` WHERE `id`=? AND `uid`=?");
  		$sql->execute(array($id, $who));
  		$sql=$OP->dbh->prepare("UPDATE `posts` SET `comments`=`comments`-1 WHERE `id`=?");
  		$sql->execute(array($pid));
  		$sql=$OP->dbh->prepare("DELETE FROM `commentLikes` WHERE `cid`=?");
  		$sql->execute(array($id));
?>
$("#<?php echo$id?>.comment").remove();$("#<?php echo$pid;?>.ck.count").text(parseFloat($("#<?php echo$pid;?>.ck.count").text())-1);
<?php
 	}
}else{
 	$OP->ser();
}
?>
