<?
include("config.php");
include("../comps/chat_rend.php");
include("../comps/notify.php");
ch();
$msg=filt($_POST['msg'],true);
$to=$_POST['to'];
$udte=false;
if($_P && $msg!="" && $to!=""){
 $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
 $sql->execute(array(":who"=>$who,":fid"=>$to));
 if($sql->rowCount()==0){
  ser();
 }
 $sql=$db->prepare("SELECT uid,msg FROM chat WHERE (uid=? AND fid=?) OR (uid=? AND fid=?) ORDER BY id DESC LIMIT 1");
 $sql->execute(array($who,$to,$to,$who));
 while($r=$sql->fetch()){
  $lu=$r['uid'];
  $lm=$r['msg'];
 }
 if($lu==$who){
  $sql=$db->prepare("UPDATE chat SET msg=?,red='0',posted=NOW() WHERE uid=? AND fid=? ORDER BY id DESC LIMIT 1");
  $sql->execute(array($lm."<br/>".$msg,$who,$to));
  $udte=true;
 }else{
  $sql=$db->prepare("INSERT INTO chat (uid,fid,msg,posted) VALUES (?,?,?,NOW())");
  $sql->execute(array($who,$to,$msg));
 }
 if(get("status",$to)=="off"){
  notify("msg",$msg,0,$to,$who);
 }
 $sql=$db->prepare("SELECT id FROM chat WHERE uid=? AND fid=? ORDER BY id DESC LIMIT 1");
 $sql->execute(array($who,$to));
 while($r=$sql->fetch()){
  $cid=$r['id'];
 }
 $ht=rendFilt(show_chat($to,$cid));
?>
  p="<?echo$ht;?>";
  <?if($udte==false){?>
   $("#<?echo$to;?>.msgs").append(p);
  <?}else{?>
   $("#<?echo$cid;?>.msg").replaceWith(p);
  <?}?>
  $("#<?echo$to;?>.chat_form")[0].reset();mcTop();
<?
}else{
 ser();
}
?>
