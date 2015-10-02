<?php
require_once "$docRoot/inc/render.php";
\Fr\LS::init();

$msg = $OP->format($_POST['msg'], true);
$to = $_POST['to'];
$udte = false; // If the message is a single item and not multiple items
if($_P && $msg != "" && $to != ""){
  $sql = $OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid` = :who AND `fid` = :fid AND `uid` IN (SELECT `fid` FROM `conn` WHERE `uid` = :fid)");
  $sql->execute(array(
    ":who" =>  $who,
    ":fid" =>  $to // Friend ID
  ));
  if($sql->rowCount() == 0){
    $OP->ser();
  }
  $sql=$OP->dbh->prepare("SELECT `uid`, `msg` FROM `chat` WHERE (`uid` = ? AND `fid` = ?) OR (`uid` = ? AND `fid` = ?) ORDER BY `id` DESC LIMIT 1");
  $sql->execute(array($who, $to, $to, $who));
  while($r=$sql->fetch()){
    $lu = $r['uid']; // Last user id
    $lm = $r['msg']; // Last message
  }
  if($lu == $who){
    $sql = $OP->dbh->prepare("UPDATE `chat` SET `msg` = ?, `red` = '0', `posted` = NOW() WHERE `uid` = ? AND `fid` = ? ORDER BY `id` DESC LIMIT 1");
    $sql->execute(array($lm."<br/>".$msg, $who, $to));
    $udte = true; // It's a single message that should be replaced with the existing one
  }else{
    $sql = $OP->dbh->prepare("INSERT INTO chat (`uid`, `fid`, `msg`, `posted`) VALUES (?, ?, ?, NOW())");
    $sql->execute(array($who, $to, $msg));
  }
  if(get("status", $to) == "off"){ // if friend is offline
    $OP->notify("msg", $msg, 0, $to, $who);
  }
  $sql = $OP->dbh->prepare("SELECT `id` FROM `chat` WHERE `uid` = ? AND `fid` = ? ORDER BY `id` DESC LIMIT 1");
  $sql->execute(array($who, $to));
  while($r = $sql->fetch()){
    $cid = $r['id'];
  }
  $html = $OP->rendFilt(Render::chat($to, $cid));
?>
  p="<?php echo $html;?>";
  <?php if($udte == false){?>
    if($("#<?php echo $to;?>.msgs .msg").length == 0){
      $("#<?php echo $to;?>.msgs").html(p);
    }else{
      $("#<?php echo $to;?>.msgs").append(p);
    }
  <?php 
  }else{
  ?>
    $("#<?php echo $cid;?>.msg").replaceWith(p);
  <?php 
  }
  ?>
  $("#<?php echo $to;?>.chat_form")[0].reset();open.chat.scrollToEnd();
<?php
}else{
  $OP->ser();
}
?>