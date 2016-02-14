<?php
include("../load.php");
//include(getenv("OPENSHIFT_REPO_DIR")."php/load.php");

$sql = $OP->dbh->prepare("SELECT * FROM `mails` ORDER BY `id` LIMIT 20");
$sql->execute();
if($sql->rowCount() != 0){
  while($r=$sql->fetch()){
    $id = $r['id'];
    $email = $r['email'];
    $subject = $r['sub'];
    $msg = $r['message'];
    if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/', $email)){
      $sql = $OP->dbh->prepare("DELETE FROM `mails` WHERE `id`=?");
      $sql->execute(array($id));
    }else{
      if($OP->sendEMail($email, $subject, $msg)){
        $sql = $OP->dbh->prepare("DELETE FROM `mails` WHERE `id`=?");
        $sql->execute(array($id));
      }
    }
  }
}
?>