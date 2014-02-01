<?
include("comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="chat,gadget,ac";$fs="ac,time,gadget";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content">
  <h1>Chat</h1>
  <div class="users">
   <?
   $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who) ORDER BY since");
   $sql->execute(array(":who"=>$who));
   while($r=$sql->fetch()){
    $id=$r['fid'];
    $fname=get("fname",$id,false);
    $name=get("name",$id,false);
    $img=get("avatar",$id);
    $st=get("status",$id);
    echo "<a href='chat?id=$id'><div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $st'>$st</span><span class='name' title='$name'>$fname</span></div></a>";
   }
   ?>
  </div>
  <div class="chat">
   <?
   $gid=$_GET['id'];
   if($gid!=""){
    $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
    $sql->execute(array(":who"=>$who,":fid"=>$gid));
    if($sql->rowCount()==0){
     if($gid==$who){
      ser("Uh... It's you","Why do you want to chat with yourself ?");
     }else{
      ser("Not Friends","You and $gid are not friends.");
     }
    }else{
     include("comps/chat_rend.php");
     echo show_chat($gid);
    }
   }else{
    echo "<h2>No User Selected</h2>To see messages, choose a person seen on the left table.";
   }
   ?>
  </div>
  <?if($sql->rowCount()!=0){echo"<br/><cl/>You are Chatting With <a href='".get("plink",$gid)."'>".get("name",$gid,false)."</a>";}?>
 </div>
</body></html>
