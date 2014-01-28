<?
include("comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="ac,gadget";$fs="ac,time,gadget";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content notifications">
  <h1>Notifications</h1>
  <?
  include("comps/not_rend.php");
  $sql=$db->prepare("SELECT id FROM notify WHERE uid=? ORDER BY id DESC");
  $sql->execute(array($who));
  if($sql->rowCount()==0){
   ser("No Notifications", "You don't have any notifications.");
  }else{
   $nfs="";
   while($r=$sql->fetch()){
    $nfs.=show_not($r['id']);
   }
   $nfs=str_replace('"','\"',$nfs);
  }
  echo $nfs;
  ?>
 </div>
 <?include("comps/gadget.php");?>
</body></html>
