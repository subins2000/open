<?
function show_chat($fid,$single=false){
 global$who, $db;
 if($single===false || $single===true){
  $sql=$db->prepare("SELECT * FROM chat WHERE (uid=? AND fid=?) OR (uid=? AND fid=?) ORDER BY id ASC");
  $sql->execute(array($who,$fid,$fid,$who));
 }elseif($single!==true){
  $sql=$db->prepare("SELECT * FROM chat WHERE id=?");
  $sql->execute(array($single));
 }
 if($single==false){
  $h="<div class='msgs' id='$fid'>";
 }
  if($sql->rowCount()!=0){
   while($r=$sql->fetch()){
    $id=$r['id'];
    $uid=$r['uid'];
    $img=get("avatar",$uid);
    $nm=get("name",$uid,false);
    $pl=get("plink",$uid);
    $snm=explode(" ",$nm);
    $snm=$snm[0];
    $h.="<div class='msg' id='$id'>";
     if($uid==$who){
      $h.="<div style='display: table-cell;margin-top: -5px;vertical-align: top;background: white;padding: 6px;padding-top:1px;'>";
       $h.="<div class='up'>";
        $h.="<a target='_blank' href='$pl'>$snm</a>";
        $h.="<span class='time'>{$r['posted']}</span>";
       $h.="</div>";
       $h.="<div class='cmsg'>{$r['msg']}</div>";
      $h.="</div>";
      $h.="<div style='display: table-cell;vertical-align: top;width:9%;'>";
       $h.="<a target='_blank' href='$pl'>";
        $h.="<img class='left' style='border-left:1px solid black;' height='32' width='32' src='$img'>";
       $h.="</a>";
      $h.="</div>";
     }else{      
      $h.="<div style='display: table-cell;vertical-align: top;width:9%;'>";
       $h.="<a target='_blank' href='$pl'>";
        $h.="<img class='left' style='border-right:1px solid black;' height='32' width='32' src='$img'>";
       $h.="</a>";
      $h.="</div>";
      $h.="<div style='display: table-cell;margin-top: -5px;vertical-align: top;background: white;padding: 1px 6px;'>";
       $h.="<div class='up'>";
        $h.="<a target='_blank' href='$pl'>$snm</a>";
        $h.="<span class='time'>{$r['posted']}</span>";
       $h.="</div>";
       $h.="<div class='cmsg'>{$r['msg']}</div>";
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
  $h.="<form action='ajax/msg' method='POST' class='ajax_form chat_form' id='$fid' succ='Sent Successfully' err='Sending Failed. Try again.' while='Sending'>";
   $h.="<input type='hidden' name='to' value='$fid'/>";
   $h.="<input type='text' class='msgEditor' name='msg' style='width:70%;min-width: 0px;'/>";
   $h.="<input type='submit' name='submit' style='width:18%;padding: 5px;min-width: 0px;' value='Send'/>";
  $h.="</form>";
 }
 $sql=$db->prepare("UPDATE chat SET red='1' WHERE uid=? AND fid=? AND red='0'");
 $sql->execute(array($fid,$who));
 return $h;
}
?>
