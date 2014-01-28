<?
/*
$a  - Action
$t  - Message
$p  - Post ID
$tu - The User Id of the one who did the action to.
$w  - Current User ID
*/
if(!function_exists("notify")){
 function notify($a,$t,$p,$tu,$w){
  global $db, $who;
  $dontSend=0;
  $n=get("name",$w,false);
  $sn=explode(" ",$n);
  $sn=$sn[0];
  $mail=get("username",$tu,false);
  if($tu==$who){
   $dontSend=1;
  }
  if($a=="comment" && $tu!=$who){
   $lci=$db->prepare("SELECT id FROM cmt WHERE pid=? AND uid=? ORDER BY id DESC LIMIT 1");
   $lci->execute(array($p,$who));
   $lci=$lci->fetchColumn();
   $sql=$db->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
   $sql->execute(array($tu,$who,"cmt","$lci-$p"));
   $sql=$db->prepare("SELECT cmts FROM posts WHERE id=?");
   $sql->execute(array($p));
   $cCmts=$sql->rowCount();
   $m=$n." commented on your <a href='http://open.subinsb.com/view?id=$p'>post</a> :";
   $m.="<blockquote>$t</blockquote>";
   $m.="Your post now have <b>$cCmts</b> comments.<br/><a href='http://open.subinsb.com/view?id=$p#$lci' target='_blank'><button style='padding:5px 15px;'>View Post</button></a>";
   $title="$sn Commented On Your Post";
  }
  if($a=="follow" && $tu!=$who){
   $sql=$db->prepare("SELECT COUNT(red) FROM notify WHERE uid=? AND fid=? AND ty=?");
   $sql->execute(array($tu,$who,"fol"));
   if($sql->rowCount()==0){
    $sql=$db->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
    $sql->execute(array($tu,$who,"fol",""));
    $sql=$db->prepare("SELECT fid FROM conn WHERE fid=?");
    $sql->execute(array($tu));
    $cFoll=$sql->rowCount();
    $m=$n." is now Following You.";
    $m.="<div style='margin: 10px;'><img src='".get("img",$w)."' style='display:inline-block;vertical-align:top;' height='120' width='120'/><div style='display:inline-block;vertical-align:top;width: 200px;margin-left:10px;'>$sn added you to his following list. You now have <b>$cFoll</b> followers. If you now follow this person back, you will become friends with $sn.</div></div>";
    $m.="<a href='http://open.subinsb.com/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
    $title="You Have a New Follower";
   }else{
    $dontSend=1;
   }
  }
  if($a=="mention" && $tu!=$who){
   $sql=$db->prepare("DELETE FROM notify WHERE uid=? AND fid=? AND ty=?");
   $sql->execute(array($tu,$who,"men"));
   $sql=$db->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
   $sql->execute(array($tu,$who,"men","0-$p"));
   $m="$n mentioned you in his $t. See the post to read what $sn had said about you.<br/>";
   $m.="<a href='http://open.subinsb.com/view?id=$p' target='_blank'><button style='padding:5px 15px;'>See $sn's ".strtoupper($t)."</button></a>&nbsp;&nbsp;&nbsp;";
   $m.="<a href='http://open.subinsb.com/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
   $title="$sn Mentioned You In His $t";
  }
  if($a=="msg" && $tu!=$who){
   $sql=$db->prepare("SELECT posted FROM notify WHERE uid=? AND fid=? AND ty=? ORDER BY id DESC LIMIT 1");
   $sql->execute(array($tu,$who,"msg"));
   if((strtotime($sql->fetchColumn()) < strtotime("-1 day", time())) || $sql->fetchColumn()==""){
    $sql=$db->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
    $sql->execute(array($tu,$who,"msg",""));
    $m="$n sent you a message :";
    $m.="<blockquote>$t</blockquote>";
    $m.="See the messages page to see other messages sent by $sn.";
    $m.="<a href='http://open.subinsb.com/chat?id=$w'><button style='padding:5px 15px;'>See $sn's Messages</button></a>&nbsp;&nbsp;&nbsp;";
    $m.="<a href='http://open.subinsb.com/$w'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
    $title="$sn Sent you a message";   
   }else{
    $dontSend=1;
   }
  }
  if($dontSend==0){
   send_mail($mail,$title,$m);
  }
 }
}
?>
