<?php

\Fr\LS::init();
$id=$_POST['id'];
if($_P && is_numeric($id)){
 $sql=$OP->dbh->prepare("DELETE FROM posts WHERE id=? AND uid=?");
 $sql->execute(array($id, $who));
 if($sql->rowCount()==0){
  $OP->ser();
 }else{
  $sql=$OP->dbh->prepare("DELETE FROM cmt WHERE pid=?");
  $sql->execute(array($id));
  $sql=$OP->dbh->prepare("DELETE FROM likes WHERE pid=?");
  $sql->execute(array($id));
?>
$(".post#<?php echo$id?>").remove();
<?php
 }
}else{
 $OP->ser();
}
?>
