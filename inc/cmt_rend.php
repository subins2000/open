<?
/* Renders Comments section of posts.
 * Give a post ID to the function and it will print out the HTML comments section that will contain the form and comments
 * $postCMTcount - the no of comments on post
*/
function show_cmt($pid){
 global $who, $OP;
 $postCMTcount=$OP->dbh->prepare("SELECT `pid` FROM `cmt` WHERE `pid`=?");
 $postCMTcount->execute(array($pid));
 $postCMTcount=$postCMTcount->rowCount();
 if(!isset($_POST['all'])){
  $sql=$OP->dbh->prepare("SELECT * FROM `cmt` WHERE `pid`=? ORDER BY `likes` DESC LIMIT 2");
 }else{
  $sql=$OP->dbh->prepare("SELECT * FROM `cmt` WHERE `pid`=? ORDER BY `likes` DESC");
 }
 $sql->execute(array($pid));
 $html="<div class='comments' id='$pid'>";
  $html.="<form class='cmt_form ajax_form' id='$pid' action='ajax/comment' succ='Commented' err='Failed To Comment' while='Commenting'>";
   $html.="<input type='hidden' id='clod' name='clod' value='0'/>";
   $html.="<textarea name='cmt' type='text' class='textEditor' placeholder='Your Comment Here'></textarea>";
   $html.="<input name='id' type='hidden' value='$pid'/>";
   $html.="<input type='submit' value='Comment'/>";
  $html.="</form>";
  if($sql->rowCount()!=0){
   while($r=$sql->fetch()){
    $id=$r['id'];
    $uid=$r['uid'];
    $img=get("avatar", $uid);
    $nm=get("name", $uid, false);
    $pl=get("plink", $uid, false);
    $lk=$OP->didLike($id, "cmt")===false ? "Like":"Unlike";
    $class=strtolower($lk)=="unlike" ? " unlike":"";
    $html.="<div class='comment' id='$id'>";
     $html.="<div class='left'>";
      $html.="<img src='$img' class='pimg'/>";
     $html.="</div>";
     $html.="<div class='right'>";
      $html.="<div class='top'>";
       $html.="<a href='$pl'>$nm</a>";
       $html.="<span class='time slink'>{$r['posted']}</span>";
       $html.="<div class='author_cmt_box'><div class='author_cmt_panel c_c'>";
        if($uid==$who){
         $html.="<a class='de_cmt pointer' id='$id'>Delete Comment</a>";
        }
        $html.="<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
       $html.="</div></div>";
      $html.="</div>";
      $html.="<div class='cont'>";
       $html.=$OP->format($r['cmt'], true);
      $html.="</div>";
      $html.="<div class='actions'>";
       $html.="<div class='like_bar'><a class='cmt like$class' id='$id'>$lk</a>";
        $html.="<span class='count lk' id='$id'>{$r['likes']}</span>";
        $html.="<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
       $html.="</div>";
      $html.="</div>";
     $html.="</div>";
    $html.="</div>";
   }
   if($postCMTcount>$sql->rowCount()){
    $html.="<a class='load_more_comments pointer' id='$pid'>Load More Comments</a>";
   }
  }else{
   $html.="<h2>No Comments</h2>No one has posted a comment yet on this post.<br/>Be the first one to comment !";
  }
 $html.="</div>";
 return $html;
}
?>