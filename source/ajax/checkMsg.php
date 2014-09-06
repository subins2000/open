<?php
require_once "$docRoot/inc/render.php";
$LS->init();
$to=$_POST['to'];
$lid=isset($_POST['lid']) ? $_POST['lid']:"";
$lup=false;
$isFri=false;
if($to!="gadget"){
 $sql=$OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid`=:fid AND `uid` IN (SELECT fid FROM conn WHERE `uid`=:fid)");
 $sql->execute(array(":who" => $who, ":fid"=>$to));
 if($sql->rowCount()==0){
  $OP->ser();
 }
}
if($lid!="" && $to!="gadget"){
 $sql=$OP->dbh->prepare("SELECT `id`, `red` FROM `chat` WHERE (`id` > :ex OR `id`=:ex) AND (`uid`=:fid AND `fid`=:uid) ORDER BY id ASC LIMIT 1");
 $sql->execute(array(":ex" => $lid, ":uid" => $who, ":fid"=>$to));
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
   $msg=$OP->rendFilt(Render::chat($to, $lid));  
  }else{
   if($mid!=""){
    $msg=$OP->rendFilt(Render::chat($to, $mid));
   }
  }
  if($msg!=""){
?>
   if(localStorage['onFormSion']==0){
    p="<?php echo$msg;?>";
    <?php
    if($lup){
    ?>
     $("#<?php echo$to;?>.msgs #<?php echo$lid;?>.msg").replaceWith(p);
     $(".msggt").show();
    <?php
    }else{
    ?>
     if($("#<?php echo$to;?>.msgs .msg").length==0){$("#<?php echo$to;?>.msgs").html("");}
     if($("#<?php echo$to;?>.msgs #<?php echo$mid;?>.msg").length==0){
      $("#<?php echo$to;?>.msgs").append(p);
     }
     $(".msggt").show();
    <?php
    }
    ?>
    open.chat.scrollToEnd();
   }
<?php
  }
 }
}elseif(isset($_POST['all']) && $_POST['all']=="true" && $to!="gadget"){
 $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
 $sql->execute(array(":who"=>$who, ":fid"=>$to));
 if($sql->rowCount()==0){
  $OP->ser();
 }
 $ht=$OP->rendFilt(Render::chat($to, true));
?>
 $("#<?php echo$to;?>.msgs").html("<?php echo$ht;?>");
 open.chat.scrollToEnd();
 t=$(".users #<?php echo$to;?>.user");
 t.css({background:"white",color:"black"});
 t.attr("title", "Unread Messages");
<?php
}
if($to!=""){
 $sql=$OP->dbh->prepare("SELECT `uid` FROM `chat` WHERE `fid`=:uid AND `red`='0' ORDER BY `id` LIMIT 1");
 $sql->execute(array(":uid"=>$who));
 if($sql->rowCount()!=0){
  $id=$sql->fetchColumn();
?>
  t=$(".users #<?php echo$id;?>.user");
  t.css({background:"red",color:"white"});
  t.attr("title", "Unread Messages");
<?php
 }
}
if($_P){
 $userstatus=array();
 $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)");
 $sql->execute(array(":who"=>$who));
 while($r=$sql->fetch()){
  $userstatus[$r['fid']]=get("status", $r['fid']);
 }
 $userstatus=json_encode($userstatus);
?>
 var userstatus=<?php echo$userstatus;?>;
 $(".users .user").each(function(){
  id=$(this).attr("id");
  if($(this).find(".status").text()!=userstatus[id]){
   $(this).find(".status").replaceWith("<span class='status "+userstatus[id]+"'>"+userstatus[id]+"</span>");
  }
 });
<?php
}else{
 $OP->ser();
}
?>