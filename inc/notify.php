<?
/*
$action - Action
$t      - Message
$pid    - Post ID
$to     - The User Id of the one who did the action to.
$w      - Current User ID
*/
if(!function_exists("notify")){
 function notify($action, $t, $pid, $to, $w){
  global $OP, $who, $docRoot;
  $dontSend=0;
  $n=get("name", $w,false);
  $sn=explode(" ", $n);
  $sn=$sn[0];
  $mail=get("username", $to, false);
  if($to==$who){
   $dontSend=1;
  }
  if($action=="comment" && $to!=$who){
   $lci=$OP->dbh->prepare("SELECT id FROM cmt WHERE `pid`=? AND `uid`=? ORDER BY id DESC LIMIT 1");
   $lci->execute(array($pid, $who));
   $lci=$lci->fetchColumn();
   $sql=$OP->dbh->prepare("INSERT INTO notify (`uid`, `fid`, `ty`, `post`, `posted`) VALUES (?, ?, ?, ?, NOW())");
   $sql->execute(array($to, $who, "cmt", "$lci-$pid"));
   $sql=$OP->dbh->prepare("SELECT cmts FROM posts WHERE id=?");
   $sql->execute(array($pid));
   $cCmts=$sql->rowCount();
   $m=$n." commented on your <a href='" . HOST . "/view/$pid'>post</a> :";
   $m.="<blockquote>$t</blockquote>";
   $m.="Your post now have <b>$cCmts</b> comments.<br/><a href='" . HOST . "/view/$pid#$lci' target='_blank'><button style='padding:5px 15px;'>View Post</button></a>";
   $title="$sn Commented On Your Post";
  }
  if($action=="follow" && $to!=$who){
   $sql=$OP->dbh->prepare("SELECT COUNT(red) FROM notify WHERE uid=? AND fid=? AND ty=?");
   $sql->execute(array($to, $who, "fol"));
   if($sql->fetchColumn()==0){
    $sql=$OP->dbh->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
    $sql->execute(array($to, $who, "fol", ""));
    $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE fid=?");
    $sql->execute(array($to));
    $cFoll=$sql->rowCount();
    $m=$n." is now Following You.";
    $m.="<div style='margin: 10px;'><img src='".get("img", $w)."' style='display:inline-block;vertical-align:top;' height='120' width='120'/><div style='display:inline-block;vertical-align:top;width: 200px;margin-left:10px;'>$sn added you to his following list. You now have <b>$cFoll</b> followers. If you now follow this person back, you will become friends with $sn.</div></div>";
    $m.="<a href='" . HOST . "/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
    $title="You Have a New Follower";
   }else{
    $dontSend=1;
   }
  }
  if($action=="mention" && $to!=$who){
   $sql=$OP->dbh->prepare("DELETE FROM notify WHERE uid=? AND fid=? AND ty=?"); /* Delete Existing This Action (mention) Notifications */
   $sql->execute(array($to, $who, $t=="post" ? "men":"menc"));
   $sql=$OP->dbh->prepare("INSERT INTO notify (uid, fid, ty, post, posted) VALUES (?, ?, ?, ?, NOW())"); /* And Add It again */
   if($t=="post"){
    $sql->execute(array($to, $who, "men", "0-$pid"));
   }else{
    $commentId=$OP->dbh->prepare("SELECT id FROM cmt WHERE `pid`=? AND `uid`=? ORDER BY id DESC LIMIT 1");
    $commentId->execute(array($pid, $who));
    $commentId=$commentId->fetchColumn();
    $sql->execute(array($to, $who, "menc", "$commentId-$pid"));
   }
   $m="$n mentioned you in his $t. See the $t to read what $sn had said about you.<br/>";
   if($t=="post"){
    $m.="<a href='" . HOST . "/view/$pid' target='_blank'>";
   }else{
    $m.="<a href='" . HOST . "/view/$pid#$commentId' target='_blank'>";
   }
   $m.="<button style='padding:5px 15px;'>See $sn's ".strtoupper($t)."</button></a>&nbsp;&nbsp;&nbsp;";
   $m.="<a href='" . HOST . "/$w' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
   $title="$sn Mentioned You In His $t";
  }
  if($action=="msg" && $to!=$who){
   $sql=$OP->dbh->prepare("SELECT posted FROM notify WHERE uid=? AND fid=? AND ty=? ORDER BY id DESC LIMIT 1");
   $sql->execute(array($to, $who, "msg"));
   $lps=$sql->fetchColumn();
   date_default_timezone_set("EST");
   if(strtotime($lps) < strtotime("-20 minutes") || $lps==""){
    $sql=$OP->dbh->prepare("INSERT INTO notify(uid,fid,ty,post,posted) VALUES (?,?,?,?,NOW())");
    $sql->execute(array($to, $who, "msg", ""));
    $m="$n sent you a message :";
    $m.="<blockquote>$t</blockquote>";
    $m.="See the messages page to see other messages sent by $sn.";
    $m.="<a href='" . HOST . "/chat/$w'><button style='padding:5px 15px;'>See $sn's Messages</button></a>&nbsp;&nbsp;&nbsp;";
    $m.="<a href='" . HOST . "/$w'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
    $title="$sn Sent you a message";   
   }else{
    $dontSend=1;
   }
  }
  $settings=get("NfS", $to);
  $action=str_replace("mention", "men", str_replace("follow", "fol", str_replace("comment", "cmt", $action)));
  if(is_array($settings) && isset($settings[$action])){
   $dontSend=1;
  }
  if($dontSend==0){
   /*$OP->sendEMail($mail, $title, $m); -- Not Needed Anymore*/
   $sql=$OP->dbh->prepare("INSERT INTO mails (email, sub, message) VALUES (?, ?, ?)");
   $sql->execute(array($mail, $title, $m));
  }
 }
}
?>