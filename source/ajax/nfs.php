<?php
require_once "$docRoot/inc/render.php";

$LS->init();
if(isset($_POST['load'])){
 $sql=$OP->dbh->prepare("SELECT id FROM notify WHERE uid=? ORDER BY id DESC LIMIT 10");
 $sql->execute(array($who));
 if($sql->rowCount()==0){
  echo '$(".notifications .loading").hide();$(".notifications .nfs").html("<br/><br/><center><h2>No Notifications</h2></center>");';
 }else{
  $nfs="";
  while($r=$sql->fetch()){
   $nfs .= Render::notification($r['id']);
  }
  $nfs=$OP->rendFilt($nfs);
  $nfs.="<br/><a href='". Open::URL("/notifications") ."' style='text-align:center;display:block;'>See All Notifications</a><br/>";
?>
$(".notifications .loading").hide();
$(".notifications .nfs").html("<?php echo $nfs;?>");
$(".notifications #nfn_button").text("0");
$(".notifications #nfn_button").removeClass("b-red");
<?php  
 }
}
?>