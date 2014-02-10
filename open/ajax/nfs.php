<?
include("config.php");
ch();
include("../comps/not_rend.php");
if(isset($_POST['load'])){
 $sql=$db->prepare("SELECT id FROM notify WHERE uid=? ORDER BY id DESC LIMIT 10");
 $sql->execute(array($who));
 if($sql->rowCount()==0){
  echo '$(".notifications .loading").hide();$(".notifications .nfs").html("<center>No Notifications</center>");';
 }else{
  $nfs="";
  while($r=$sql->fetch()){
   $nfs.=show_not($r['id']);
  }
  $nfs=rendFilt($nfs);
  $nfs.="<br/><a href='notifications' style='text-align:center;display:block;'>See All Notifications</a><br/>";
?>
$(".notifications .loading").hide();
$(".notifications .nfs").html("<?echo$nfs;?>");
$(".notifications #nfn_button").text("0");
$(".notifications #nfn_button").removeClass("b-red");
<?  
 }
}
?>
