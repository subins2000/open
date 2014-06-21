<?
function show_chat($fid, $single=false){
 global $who, $OP;
 if($single===false || $single===true){
  $sql=$OP->dbh->prepare("SELECT * FROM (SELECT * FROM `chat` WHERE (`uid`=? AND `fid`=?) OR (`uid`=? AND `fid`=?) ORDER BY id DESC LIMIT 15) sub ORDER BY `id` ASC");
  $sql->execute(array($who, $fid, $fid, $who));
 }elseif($single!==true){
  $sql=$OP->dbh->prepare("SELECT * FROM chat WHERE id=?");
  $sql->execute(array($single));
 }
 $h="";
 if($single==false){
  $h="<div class='msgs' id='$fid'>";
 }
  if($sql->rowCount()!=0){
   while($r=$sql->fetch()){
    $id	= $r['id'];
    $uid	= $r['uid'];
    $img	= get("avatar", $uid);
    $nm	= get("name", $uid, false);
    $pl	= get("plink", $uid);
    $snm	= explode(" ", $nm);
    $snm	= $snm[0];
    $h.="<div class='msg' id='$id'>";
     if($uid == $who){
      $h.="<div class='left'>";
      	$h.="<div class='mainContent'>";
       		$h.="<div class='up'>";
        			$h.="<a target='_blank' href='$pl'>$snm</a>";
        			$h.="<span class='time'>{$r['posted']}</span>";
       		$h.="</div>";
       		$h.="<div class='cmsg'>{$r['msg']}</div>";
       	$h.="</div>";
       	$h.="<div class='avatar'>";
       		$h.="<a target='_blank' href='$pl'>";
        			$h.="<img height='32' width='32' src='$img'>";
       		$h.="</a>";
      	$h.="</div>";
      $h.="</div>";
     }else{      
      $h.="<div class='right'>";
      	$h.="<div class='mainContent'>";
       		$h.="<div class='up'>";
        			$h.="<a target='_blank' href='$pl'>$snm</a>";
        			$h.="<span class='time'>{$r['posted']}</span>";
       		$h.="</div>";
       		$h.="<div class='cmsg'>{$r['msg']}</div>";
       	$h.="</div>";
       	$h.="<div class='avatar'>";
     			$h.="<a target='_blank' href='$pl'>";
     				$h.="<img height='32' width='32' src='$img'>";
    			$h.="</a>";
     		$h.="</div>";
      $h.="</div>";
     }
    $h.="</div>";
   }
  }else{
   $h.="<h2>No Messages</h2>You haven't exchanged messages with this gal. Spark up a conversation.";
  }
 if($single===false){
  $h.="</div>";
 }
 if($single===false){
  $h.="<form action='ajax/msg' method='POST' class='ajax_form chat_form blocks' id='$fid' succ='Sent Successfully' err='Sending Failed. Try again.' while='Sending'>";
   $h.="<input type='hidden' name='to' value='$fid'/>";
   $h.="<textarea type='text' class='msgEditor block' name='msg' style='width:70%;min-width: 0px;'></textarea>";
   $h.="<input type='submit' name='submit' class='block' value='Send'/>";
  $h.="</form>";
 }
 $sql=$OP->dbh->prepare("UPDATE chat SET red='1' WHERE uid=? AND fid=? AND red='0'");
 $sql->execute(array($fid, $who));
 return $h;
}
?>
