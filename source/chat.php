<?

$OP->init();
if($_SERVER['SCRIPT_NAME']=="/chat.php" && isset($_GET['q'])){/* We don't want view?id= URLs anymore */
 $To=$_GET['id']=="" ? "":"/{$_GET['id']}";
 $OP->$OP->redirect("/chat$To", 301); /* 3rd Param is the status code and not the 2nd */
}
?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("", "ac,time,gadget", "chat,gadget,ac");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content">
  <div class="users">
   <?
   $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who) ORDER BY `since`");
   $sql->execute(array(":who"=>$who));
   while($r=$sql->fetch()){
    $id=$r['fid'];
    $fname=get("fname", $id,false);
    $name=get("name", $id,false);
    $img=get("avatar", $id);
    $st=get("status", $id);
    echo "<a href='/chat/$id'><div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $st'>$st</span><span class='name' title='$name'>$fname</span></div></a>";
   }
   ?>
  </div>
  <div class="chat">
   <?
   $_GET['id']=isset($_GET['id']) ? $_GET['id']:"";
   $gid=$OP->format($_GET['id']);
   if($gid!=""){
    $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE uid=:who AND fid=:fid AND uid IN (SELECT fid FROM conn WHERE uid=:fid)");
    $sql->execute(array(":who"=>$who, ":fid"=>$gid));
    if($sql->rowCount()==0){
     if($gid==$who){
      echo "<h2>Uh... It's you</h2><p>Why do you want to chat with yourself ?</p>";
     }else{
      echo "<h2>Not Friends</h2><p>You and $gid are not friends.</p>";
     }
    }else{
     $OP->inc("inc/chat_rend.php");
     echo show_chat($gid);
    }
   }else{
    echo "<h2>No User Selected</h2>To see messages, choose a person seen on the left table.";
   }
   ?>
  </div>
  <?if($sql->rowCount()!=0 && $_GET['id']!=""){echo"<br/><cl/>Chatting With <a href='".get("plink", $gid)."'>".get("name", $gid,false)."</a>";}?>
 </div>
</body></html>
