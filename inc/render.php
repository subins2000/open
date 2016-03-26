<?php
class Render {
  
  /**
   * The $single variable is for displaying a single message. It should contain the message ID.
   */
  public static function chat($fid, $single = false){
    
    if($single === false || $single === true){
      $sql = $GLOBALS['OP']->dbh->prepare("SELECT * FROM (SELECT * FROM `chat` WHERE (`uid` = :who AND `fid` = :fid) OR (`uid` = :fid AND `fid` = :who) ORDER BY id DESC LIMIT 15) sub ORDER BY `id` ASC");
      $sql->execute(array(
        ":who" => curUser,
        ":fid" => $fid
      ));
    }elseif($single !== true){
      $sql = $GLOBALS['OP']->dbh->prepare("SELECT * FROM `chat` WHERE `id` = ?");
      $sql->execute(array($single));
    }
    $html = "";
    if($single == false){
      $html = "<div class='msgs' id='$fid'>";
    }
    if($sql->rowCount()!=0){
      while($r = $sql->fetch()){
        $mid = $r['id']; // Message ID
        $uid = $r['uid']; // User ID
        $img = get("avatar", $uid); // Avatar Image
        $name = get("name", $uid, false); // Name
        $pLink = get("plink", $uid); // Profile URL
        $fName = get("fname", $uid); // First Name
        $html  .= "<div class='msg' id='$mid'>";
        if($uid == curUser){
          $html.="<div class='leftBox row'>";
            $html.="<div class='mainContent col m12'>";
              $html.="<div class='up'>";
                $html.="<a target='_blank' href='$pLink' title='{$name}'>{$fName}</a>";
                $html.="<span class='time'>{$r['posted']}</span>";
              $html.="</div>";
              $html.="<div class='cmsg'>{$r['msg']}</div>";
            $html.="</div>";
            $html.="<div class='avatar left'>";
              $html.="<a target='_blank' href='$pLink'>";
                $html.="<img height='32' width='32' src='$img' class='circle responsive-img'>";
              $html.="</a>";
            $html.="</div>";
          $html.="</div>";
        }else{      
          $html.="<div class='rightBox row'>";
            $html.="<div class='mainContent col m12 right'>";
              $html.="<div class='up'>";
                $html.="<a target='_blank' href='$pLink'>$fName</a>";
                $html.="<span class='time'>{$r['posted']}</span>";
              $html.="</div>";
              $html.="<div class='cmsg'>{$r['msg']}</div>";
            $html.="</div>";
            $html.="<div class='avatar right'>";
              $html.="<a target='_blank' href='$pLink'>";
                $html.="<img height='32' width='32' src='$img'>";
              $html.="</a>";
            $html.="</div>";
          $html.="</div>";
        }
        $html.="</div>";
      }
    }else{
      $html.="<h4>No Messages</h4>You haven't exchanged messages with this gal. Spark up a conversation.";
    }
    if( !$single ){
      $html.="</div>";
    }
    if( !$single ){
      $html .= "<form action='ajax/msg' method='POST' class='ajax_form chat_form blocks' id='$fid' success='Sent Successfully' error='Sending Failed. Try again.' while='Sending'>";
        $html .= "<input type='hidden' name='to' value='$fid'/>";
        $html .= "<textarea type='text' class='msgEditor' name='msg' placeholder='Type here...'></textarea>";
        $html .= "<input type='submit' name='submit' style='display: none;'/>";
      $html .= "</form>";
    }
    $sql = $GLOBALS['OP']->dbh->prepare("UPDATE `chat` SET `red`='1' WHERE `uid`=? AND `fid`=? AND `red`='0'");
    $sql->execute(array($fid, curUser));
    return $html;
  }
  
  public static function post($postArr) {
    
     $html = "";
    if(count($postArr) == 0){
        $html = "<h4><center>No Posts Found</center></h4>";
    }else{
      $last = -1;
      $i = 0;
      /* $v contains information about the post*/
      foreach($postArr as $v){
        $owner = $v['uid']; /* The user ID of the post owner */
        $id = $v['id']; /* The Post ID */
      
        $privacyShort = str_replace('pub', '<i class="material-icons">public</i>',
          str_replace('meo', '<i class="material-icons">account_circle</i>',
            str_replace('fri', '<i class="material-icons">people</i>', 
              $v['privacy']
            )
          )
        );
        $privacyDescription = str_replace('pub','Everyone can see this post', 
          str_replace('meo','Only You can see this post',
            str_replace('fri','Only your Friends can see this post', 
              $v['privacy']
            )
          )
        );
  
        $liked = $GLOBALS['OP']->didLike($id, "post") === false ? "Like" : "Unlike";
        $class = strtolower($liked) == "unlike" ? " unlike" : "";
        $post = $v['post'];
        $otherSTR = false;
        
        if( strlen($post) > 500 ){
          $postSplit = str_split($post, 500);
          $post = $postSplit[0];
          $otherSTR = str_replace($post, "", $v['post']); // The left post
          $otherSTR = $GLOBALS['OP']->format($otherSTR, true);
        }
        
        /* We format the post from @1 to @Subin Siby */
        $post = $GLOBALS['OP']->format($post, true);
        
        /* The Profile Link */
        $plink = get("plink", $owner);
  
          $html .= "<div class='post blocks row' id='$id'>";
            $html .= "<div class='col m1'>";
              $html .= "<a href='$plink'><img src='".get("avatar", $owner)."' class='pimg circle responsive-img'/></a>";
            $html .= "</div>";
            $html .= "<div class='col m11'>";
              $html .= "<div class='top'>";
                $html .= "<a href='$plink'>".get("name", $owner, false)."</a>";
                $html .= "<span class='privacy slink' title='{$privacyDescription}'>{$privacyShort}</span>";
                $html .= "<a class='time slink' href='" . O_URL . "/view/{$id}'>{$v['time']}</a>";
                if($owner == curUser){
                  $html .= "<div class='author_box'>";
                    $html .= "<div class='card author_panel c_c'>";
                      $html .= "<a class='postLink pointer'><i class='material-icons'>link</i></a>";
                      $html .= "<a class='editPost pointer'><i class='material-icons'>mode_edit</i></a>";
                      $html .= "<a class='deletePost pointer'><i class='material-icons'>delete</i></a>";
                    $html .= "</div>";
                  $html .= "</div>";
                }
              $html .= "</div>";
              $html .= "<div class='cont'>";
              if( $otherSTR ){
                $html .= $post . "<a class='btn green readMore'>Read More <div hide>{$otherSTR}</div></a>";
              }else{
                $html .= $post;
              }
              if( $v['image'] != "" ){
                CLEAN_HOST != "open.sim" ? $v['image'] = str_replace("http:", "https:", $v['image']) : "";
                $html .= "<img class='postImage responsive-img' data-fullsize='{$v['image']}' src='{$v['image']}/200' />";
              }
              $html .= "</div>";
              $html .= "<div class='bottom'>";
                 $html .= "<div class='like_bar'><a class='pst like$class' id='$id'>$liked</a><span class='pst count lk' id='$id'>{$v['likes']}</span></div>";
                 $html .= "<div class='cmt_bar'><a class='pst cmt' id='$id'>Comment</a><span class='count ck' id='$id'>{$v['comments']}</span></div>";
                 $html .= self::comment($id);
              $html .= "</div>";
          $html .= "</div>";
        $html .= "</div>";
        
        if($last != $i - 1 && rand(0, 20) % 6 == 0){
          $html .= require docRoot . "/inc/suggest.php";
          $last = $i;
        }
        $i++;
      }
    }
    return $html;
  }
  
  public static function comment($pid = false) {
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT COUNT(*) FROM `comments` WHERE `pid` = '381'");
    $sql->execute();
    $postCMTcount = $sql->fetchColumn();
 
    if(!isset($_POST['all'])){
      $sql = $GLOBALS['OP']->dbh->prepare("SELECT * FROM `comments` WHERE `pid`=? ORDER BY `likes` DESC, `time` DESC LIMIT 2");
    }else{
      $sql = $GLOBALS['OP']->dbh->prepare("SELECT * FROM `comments` WHERE `pid`=? ORDER BY `likes` DESC, `time` DESC");
    }
    $sql->execute(array($pid));
 
    $html = "<div class='comments' id='$pid'>";
      $html .= "<form class='cmt_form ajax_form row' id='$pid' action='ajax/comment' success='Commented' error='Failed To Comment' while='Commenting'>";
        $html .= "<input type='hidden' id='clod' name='clod' value='0'/>";
        $html .= "<textarea name='cmt' type='text' class='textEditor col s10 materialize-textarea' placeholder='Your Comment Here'></textarea>";
        $html .= "<input name='id' type='hidden' value='$pid'/>";
        $html .= "<button class='btn blue s2'>Comment</button>";
      $html .= "</form>";
      
      $displayedComments = $sql->rowCount();
      if($displayedComments != 0){
        while( $r = $sql->fetch() ){
          $id = $r['id'];
          $uid = $r['uid'];
          $img = get("avatar", $uid);
          $name = get("name", $uid, false);
          $pLink = get("plink", $uid, false);
          $lk = $GLOBALS['OP']->didLike($id, "cmt") === false ? "Like":"Unlike";
          $class = strtolower($lk) == "unlike" ? " unlike":"";
          $html .= "<div class='comment row' id='$id'>";
            $html .= "<div class='col m1'>";
              $html .= "<img src='$img' class='pimg'/>";
            $html .= "</div>";
            $html .= "<div class='col m11'>";
              $html .= "<div class='top'>";
                $html .= "<a href='{$pLink}'>$name</a>";
                $html .= "<a class='time slink' href='" . O_URL . "/view/{$r['pid']}#$id'>{$r['time']}</a>";
                $html .= "<div class='author_cmt_box'><div class='author_cmt_panel c_c'>";
                  if($uid == curUser){
                    $html .= "<a class='de_cmt pointer' id='$id'>Delete Comment</a>";
                  }
                  $html .= "<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
                $html .= "</div></div>";
              $html .= "</div>";
              $html .= "<div class='cont'>";
                $html .= $GLOBALS['OP']->format($r['comment'], true);
              $html .= "</div>";
              $html .= "<div class='actions'>";
                $html .= "<div class='like_bar'><a class='cmt like$class' id='$id'>$lk</a>";
                  $html .= "<span class='count lk' id='$id'>{$r['likes']}</span>";
                  $html .= "<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
                $html .= "</div>";
              $html .= "</div>";
            $html .= "</div>";
          $html .= "</div>";
        }
        if($postCMTcount > $displayedComments){
          $html .= "<a class='load_more_comments pointer' id='$pid'>Load More Comments</a>";
        }
      }else{
        $html .= "<h4>No Comments</h4>No one has posted a comment yet on this post.<br/>Be the first one to comment !";
      }
     $html .= "</div>";
    return   $html;
  }
  
  public static function notification($id){
    
    
    $sql = $GLOBALS['OP']->dbh->prepare("SELECT * FROM `notify` WHERE id=?");
    $sql->execute(array($id));
    while($r = $sql->fetch()){
      $fid = $r['fid'];
      $img = get("avatar", $fid);
      $name = get("fname", $fid, false);
      if(preg_match("/\-/", $r['post'])){
        list($aid, $pid) = explode("-", $r['post']);
      }
      if($r['ty'] == "cmt"){
        $amsg = "commented on your post";
        $alnk = Open::URL("view/{$pid}#$aid");
      }elseif($r['ty'] == "fol"){
        $amsg = "is now following you";
        $alnk = get("plink", $r['fid']);
      }elseif($r['ty'] == "msg"){
        $amsg = "sent you a message";
        $alnk = Open::URL("/chat/{$r['fid']}");
      }elseif($r['ty'] == "men"){
        $amsg = "mentioned you in a post";
        $alnk = Open::URL("/view/$pid");
      }elseif($r['ty'] == "menc"){
        $amsg = "mentioned you in a comment";
        $alnk = Open::URL("/view/$pid");
      }else{
        $amsg = "";
        $alnk = "";
      }
      $iuR = $r['red'] == 0 ? "nred":"";
      $iuT = $r['red'] == 0 ? "Unread Notification":"";
      $nfs="<a href='$alnk'>";
        $nfs.="<div class='nfsi row $iuR' id='$id' title='$iuT'>";
          $nfs.="<div class='col s2'>";
            $nfs.="<img class='responsive-img' src='$img'/>";
          $nfs.="</div>";
          $nfs.="<div class='col s10'>";
            $nfs.="<span class='name'>$name</span><br/>";
            $nfs.="<span class='time'>{$r['posted']}</span>";
            $nfs.="<div class='cont'>";
              $nfs .= $amsg;
            $nfs.="</div>";
          $nfs.="</div>";
        $nfs.="</div>";
      $nfs.="</a>";
    }
    $sql = $GLOBALS['OP']->dbh->prepare("UPDATE `notify` SET red='1' WHERE `id` = ?");
    $sql->execute(array($id));
    return $nfs;
  }
}
?>
