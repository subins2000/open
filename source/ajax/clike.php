<?

$OP->init();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$OP->dbh->prepare("SELECT id FROM cmt WHERE id=?");
 $sql->execute(array($id));
 if($sql->rowCount()!=0){
  $sql=$OP->dbh->prepare("SELECT * FROM clikes WHERE uid=? AND cid=?");
  $sql->execute(array($who, $id));
  if($sql->rowCount()==0){
   $sql=$OP->dbh->prepare("INSERT INTO clikes (uid,cid,liked) VALUES (?,?,NOW())");
   $sql->execute(array($who, $id));
   $sql=$OP->dbh->prepare("UPDATE cmt SET likes=likes+1 WHERE id=?");
   $sql->execute(array($id));
  }else{
   $sql=$OP->dbh->prepare("DELETE FROM clikes WHERE uid=? AND cid=?");
   $sql->execute(array($who, $id));
   $sql=$OP->dbh->prepare("UPDATE cmt SET likes=likes-1 WHERE id=?");
   $sql->execute(array($id));
  }
 }
}else{
 $OP->ser();
}
?>
