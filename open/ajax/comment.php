<?
include("config.php");
include("../comps/cmt_rend.php");
ch();
$id=$_POST['id'];
$msg=filt($_POST['cmt'],true);
if($_P && is_numeric($id) && preg_match("/[^\s]/",$msg)){
 $sql=$db->prepare("SELECT id,uid FROM posts WHERE id=?");
 $sql->execute(array($id));
 $prr=$sql->fetch();
 $prr=$prr['uid'];
 if($sql->rowCount()!=0){
  $sql=$db->prepare("INSERT INTO cmt(uid,pid,cmt,posted) VALUES(?,?,?,NOW())");
  $sql->execute(array($who,$id,$msg));
  $sql=$db->prepare("UPDATE posts SET cmts=cmts+1 WHERE id=?");
  $sql->execute(array($id));
  include("../comps/notify.php");
  #notify("comment",$msg,$id,$prr,$who);
  if($_POST['clod']=='mom'){
   $_POST['all']=1;
  }
  $ht=rendFilt(show_cmt($id));
?>
$("#<?echo$id;?>.comments").replaceWith("<?echo$ht;?>");$("#<?echo$id;?>.comments").show();$("#<?echo$id;?>.ck.count").text(parseFloat($("#<?echo$id;?>.ck.count").text())+1);
<?
 }else{
  ser();
 }
}else{
 ser();
}
?>
