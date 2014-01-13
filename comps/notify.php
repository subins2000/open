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
   $sql=$db->prepare("SELECT cmts FROM posts WHERE id=?");
   $sql->execute(array($p));
   $cCmts=$sql->rowCount();
   $m=$n." commented on your <a href='http://open.subinsb.com/view?id=$p'>post</a> :";
   $m.="<blockquote>$t</blockquote>";
   $m.="Your post now have <b>$cCmts</b> comments.<br/><a href='http://open.subinsb.com/view?id=$p' target='_blank'><button style='padding:5px 15px;'>View Post</button></a>";
   $title="$sn Commented On Your Post";
  }
  if($a=="follow" && $tu!=$who){
   $sql=$db->prepare("SELECT fid FROM conn WHERE fid=?");
   $sql->execute(array($tu));
   $cFoll=$sql->rowCount();
   $m=$n." is now Following You.";
   $m.="<div style='margin: 10px;'><img src='".get("img",$w)."' style='display:inline-block;vertical-align:top;' height='120' width='120'/><div style='display:inline-block;vertical-align:top;width: 200px;margin-left:10px;'>$sn added you to his following list. You now have <b>$cFoll</b> followers. If you now follow this person back, you will become friends with $sn.</div></div>";
   $m.="<a href='http://open.subinsb.com/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
   $title="You Have a New Follower";
  }
  if($a=="mention" && $tu!=$who){
   $m="$n mentioned you in his $t. See the post to read what $sn had said about you.<br/>";
   $m.="<a href='http://open.subinsb.com/view?id=$p' target='_blank'><button style='padding:5px 15px;'>See $sn's ".strtoupper($t)."</button></a>&nbsp;&nbsp;&nbsp;";
   $m.="<a href='http://open.subinsb.com/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
   $title="$sn Mentioned You In His $t";
  }
  if($dontSend==0){
   send_mail($mail,$title,$m);
  }
 }
}
?>
