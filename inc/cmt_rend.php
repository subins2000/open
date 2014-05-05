<?
function show_cmt($pid){
 global $who, $db, $OP;
 $ocnt=$db->prepare("SELECT * FROM cmt WHERE pid=?");
 $ocnt->execute(array($pid));
 $ocnt=$ocnt->rowCount();
 if(!isset($_POST['all'])){
  $sql=$db->prepare("SELECT * FROM cmt WHERE pid=? ORDER BY likes DESC LIMIT 2");
 }else{
  $sql=$db->prepare("SELECT * FROM cmt WHERE pid=? ORDER BY likes DESC");
 }
 $sql->execute(array($pid));
 $h="<div class='comments' id='$pid'>";
  $h.="<form class='cmt_form ajax_form' id='$pid' action='ajax/comment' succ='Commented' err='Failed To Comment' while='Commenting'>";
   $h.="<input type='hidden' id='clod' name='clod' value='0'/>";
   $h.="<textarea name='cmt' type='text' class='textEditor' placeholder='Your Comment Here'></textarea>";
   $h.="<input name='id' type='hidden' value='$pid'/>";
   $h.="<input type='submit' value='Comment'/>";
  $h.="</form>";
  if($sql->rowCount()!=0){
   while($r=$sql->fetch()){
    $id=$r['id'];
    $uid=$r['uid'];
    $img=get("avatar",$uid);
    $nm=get("name", $uid, false);
    $pl=get("plink", $uid, false);
    $lk=$OP->didLike($id, "cmt")===false ? "Like":"Unlike";
    $class=strtolower($lk)=="unlike" ? " unlike":"";
    $h.="<div class='comment' id='$id'>";
     $h.="<div class='left'>";
      $h.="<img src='$img' class='pimg'/>";
     $h.="</div>";
     $h.="<div class='right'>";
      $h.="<div class='top'>";
       $h.="<a href='$pl'>$nm</a>";
       $h.="<span class='time slink'>{$r['posted']}</span>";
       if($uid==$who){
        $h.="<div class='author_cmt_box'><div class='author_cmt_panel c_c'><a class='de_cmt pointer' id='$id'>Delete Comment</a><a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a></div></div>";
       }
      $h.="</div>";
      $h.="<div class='cont'>";
       $h.=filt($r['cmt'], true);
      $h.="</div>";
      $h.="<div class='actions'>";
       $h.="<div class='like_bar'><a class='cmt like$class' id='$id'>$lk</a>";
        $h.="<span class='count lk' id='$id'>{$r['likes']}</span>";
        $h.="<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
       $h.="</div>";
      $h.="</div>";
     $h.="</div>";
    $h.="</div>";
   }
   if($ocnt>$sql->rowCount()){
    $h.="<a class='load_more_comments pointer' id='$pid'>Load More Comments</a>";
   }
  }else{
   $h.="<h2>No Comments</h2>No one has posted a comment yet on this post.<br/>Be the first one to comment !";
  }
 $h.="</div>";
 return $h;
}
?>
