<?php
$LS->init();
$id = isset($_POST['id']) ? $_POST['id'] : "";
if( $_P && is_numeric($id) ){
	$sql = $OP->dbh->prepare("SELECT id FROM posts WHERE id=?");
	$sql->execute(array($id));
	if($sql->rowCount()!=0){
		$sql = $OP->dbh->prepare("SELECT * FROM likes WHERE uid=? AND pid=?");
		$sql->execute(array($who, $id));
		if($sql->rowCount() == 0){
			$sql = $OP->dbh->prepare("INSERT INTO likes (uid,pid,liked) VALUES (?,?,NOW())");
			$sql->execute(array($who, $id));
			$sql = $OP->dbh->prepare("UPDATE posts SET likes=likes+1 WHERE id=?");
			$sql->execute(array($id));
		}else{
			$sql = $OP->dbh->prepare("DELETE FROM likes WHERE uid=? AND pid=?");
			$sql->execute(array($who, $id));
			$sql = $OP->dbh->prepare("UPDATE posts SET likes=likes-1 WHERE id=?");
			$sql->execute(array($id));
		}
	}
}else{
	$OP->ser();
}
?>