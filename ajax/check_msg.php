<?
include("config.php");
include("../comps/chat_rend.php");
ch();
$to=$_GET['to'];
$lid=$_GET['lid'];
$lup=false;
if($to!="" && $lid!=""){
 $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
 $sql->execute(array(":who"=>$who,":fid"=>$to));
 if($sql->rowCount()==0){
  ser();
 }
 $sql=$db->prepare("SELECT id,red FROM chat WHERE (id > :ex OR id=:ex) AND (uid=:fid AND fid=:uid) ORDER BY id ASC LIMIT 1");
 $sql->execute(array(":ex"=>$lid,":uid"=>$who,":fid"=>$to));
 if($sql->rowCount()!=0){
  while($r=$sql->fetch()){
   if($r['id']!=$lid){
    $mid=$r['id'];
   }else{
    if($r['red']==0){
     $lup=true;
    }
   }
  }
  if($lup){
   $msg=str_replace('"','\"',show_chat($to,$lid));  
  }else{
   if($mid==""){
    exit;
   }
   $msg=str_replace('"','\"',show_chat($to,$mid));
  }
?>
  if(localStorage['onFormSion']==0){
   p="<?echo$msg;?>";
   <?
   if($lup){
   ?>
    $("#<?echo$to;?>.msgs #<?echo$lid;?>.msg").replaceWith(p);
   <?
   }else{
   ?>
    if($("#<?echo$to;?>.msgs .msg").length==0){$("#<?echo$to;?>.msgs").html("");}
    $("#<?echo$to;?>.msgs").append(p);
   <?
   }
   ?>
   mcTop();
  }
<?
 }
}
?>
