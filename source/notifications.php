<?
$OP->init();
?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("", "ac,time,gadget", "ac,gadget");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content notifications">
  <h1>Notifications</h1>
  <?
  $OP->inc("inc/not_rend.php");
  $sql=$OP->dbh->prepare("SELECT `id` FROM `notify` WHERE `uid`=? ORDER BY `id` DESC");
  $sql->execute(array($who));
  if($sql->rowCount()==0){
   $OP->ser("No Notifications", "You don't have any notifications.");
  }else{
   $nfs="";
   while($r=$sql->fetch()){
    $nfs.=show_not($r['id']);
   }
   $nfs=str_replace('"', '\"', $nfs);
  }
  echo $nfs;
  ?>
  <p>Notifications older than 50 days are removed</p>
 </div>
 <?$OP->inc("inc/gadget.php");?>
</body></html>