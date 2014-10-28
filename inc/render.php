<?php
class Render {
	
	/* The $single variable is for displaying a single message. It should contain the message ID. */
	public static function chat($fid, $single = false){
		global $OP;
		if($single === false || $single === true){
			$sql = $OP->dbh->prepare("SELECT * FROM (SELECT * FROM `chat` WHERE (`uid` = :who AND `fid` = :fid) OR (`uid` = :fid AND `fid` = :who) ORDER BY id DESC LIMIT 15) sub ORDER BY `id` ASC");
			$sql->execute(array(
				":who" => curUser,
				":fid" => $fid
			));
		}elseif($single !== true){
			$sql = $OP->dbh->prepare("SELECT * FROM `chat` WHERE `id` = ?");
			$sql->execute(array($single));
		}
		$html = "";
		if($single == false){
			$html = "<div class='msgs' id='$fid'>";
		}
		if($sql->rowCount()!=0){
			while($r = $sql->fetch()){
				$mid	= $r['id']; // Message ID
				$uid	= $r['uid']; // User ID
				$img	= get("avatar", $uid); // Avatar Image
				$name	= get("name", $uid, false); // Name
				$pLink	= get("plink", $uid); // Profile URL
				$fName	= get("fname", $uid); // First Name
				$html  .= "<div class='msg' id='$mid'>";
				if($uid == curUser){
					$html.="<div class='left'>";
						$html.="<div class='mainContent'>";
							$html.="<div class='up'>";
								$html.="<a target='_blank' href='$pLink' title='{$name}'>{$fName}</a>";
								$html.="<span class='time'>{$r['posted']}</span>";
							$html.="</div>";
							$html.="<div class='cmsg'>{$r['msg']}</div>";
						$html.="</div>";
						$html.="<div class='avatar'>";
							$html.="<a target='_blank' href='$pLink'>";
								$html.="<img height='32' width='32' src='$img'>";
							$html.="</a>";
						$html.="</div>";
					$html.="</div>";
				}else{      
					$html.="<div class='right'>";
						$html.="<div class='mainContent'>";
							$html.="<div class='up'>";
								$html.="<a target='_blank' href='$pLink'>$fName</a>";
								$html.="<span class='time'>{$r['posted']}</span>";
							$html.="</div>";
							$html.="<div class='cmsg'>{$r['msg']}</div>";
						$html.="</div>";
						$html.="<div class='avatar'>";
							$html.="<a target='_blank' href='$pLink'>";
								$html.="<img height='32' width='32' src='$img'>";
							$html.="</a>";
						$html.="</div>";
					$html.="</div>";
				}
				$html.="</div>";
			}
		}else{
			$html.="<h2>No Messages</h2>You haven't exchanged messages with this gal. Spark up a conversation.";
		}
		if( !$single ){
			$html.="</div>";
		}
		if( !$single ){
			$html .= "<form action='ajax/msg' method='POST' class='ajax_form chat_form blocks' id='$fid' success='Sent Successfully' error='Sending Failed. Try again.' while='Sending'>";
				$html .= "<input type='hidden' name='to' value='$fid'/>";
				$html .= "<textarea type='text' class='msgEditor block' name='msg'></textarea>";
				$html .= "<input type='submit' name='submit' class='block' value='Send'/>";
			$html .= "</form>";
		}
		$sql = $OP->dbh->prepare("UPDATE `chat` SET `red`='1' WHERE `uid`=? AND `fid`=? AND `red`='0'");
		$sql->execute(array($fid, curUser));
		return $html;
	}
	
	public static function post($postArr) {
		global $OP;
 		$html = "";
		if(count($postArr) == 0){
  			$html = "<h2><center>No Posts Found</center></h2>";
		}else{
			/* $v contains information about the post*/
			foreach($postArr as $v){
				$owner	= $v['uid']; /* The user ID of the post owner */
				$id		= $v['id']; /* The Post ID */
  		
				$privacyShort = str_replace('pub','Public',
					str_replace('meo','Only Me',
						str_replace('fri','Friends', 
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
  
				$liked		= $OP->didLike($id, "post") === false ? "Like" : "Unlike";
				$class		= strtolower($liked) == "unlike" ? " unlike" : "";
				$post 		= $v['post'];
				$otherSTR 	= false;
				
				if( strlen($post) > 500 ){
					$postSplit 	= str_split($post, 500);
					$post		= $postSplit[0];
					$otherSTR 	= str_replace($post, "", $v['post']); // The left post
					$otherSTR 	= $OP->format($otherSTR, true);
				}
				
				/* We format the post from @1 to @Subin Siby */
				$post	= $OP->format($post, true);
				
				/* The Profile Link */
				$plink	= get("plink", $owner);
  
  				$html .= "<div class='post blocks' id='$id'>";
					$html .= "<div class='left block'>";
    					$html .= "<a href='$plink'><img src='".get("avatar", $owner)."' class='pimg'/></a>";
					$html .= "</div>";
					$html .= "<div class='right block'>";
    					$html .= "<div class='top'>";
     						$html .= "<a href='$plink'>".get("name", $owner, false)."</a>";
     						$html .= "<a class='time slink' href='" . HOST . "/view/{$id}'>{$v['time']}</a>";
     						$html .= "<span class='privacy slink' title='{$privacyDescription}'>{$privacyShort}</span>";
						if($owner == curUser){
      						$html .= "<div class='author_box'>";
								$html .= "<div class='author_panel c_c'>";
									$html .= "<a class='editPost pointer'>Edit Post</a><cl/>";
									$html .= "<a class='deletePost pointer'>Delete Post</a><cl/>";
									$html .= "<a class='postLink pointer'>HyperLink</a>";
								$html .= "</div>";
							$html .= "</div>";
						}
    					$html .= "</div>";
    					$html .= "<div class='cont'>";
     						if( $otherSTR ){
								$html .= $post . "<a class='button b-green readMore'>Read More <div hide>{$otherSTR}</div></a>";
							}else{
								$html .= $post;
							}
							if( $v['image'] != "" ){
								CLEAN_HOST != "open.subinsb.com" ? $v['image'] = str_replace("http:", "https:", $v['image']) : "";
								$html .= "<img class='postImage' data-fullsize='{$v['image']}' src='{$v['image']}/500' />";
							}
    					$html .= "</div>";
    					$html .= "<div class='bottom'>";
     						$html .= "<div class='like_bar'><a class='pst like$class' id='$id'>$liked</a><span class='pst count lk' id='$id'>{$v['likes']}</span></div>";
     						$html .= "<div class='cmt_bar'><a class='pst cmt' id='$id'>Comment</a><span class='count ck' id='$id'>{$v['comments']}</span></div>";
     						$html .= self::comment($id);
    					$html .= "</div>";
					$html .= "</div>";
  				$html .= "</div>";
			}
		}
		return 	$html;
	}
	
	public static function comment($pid) {
		global $who, $OP;
 
		$postCMTcount = $OP->dbh->prepare("SELECT `pid` FROM `comments` WHERE `pid`=?");
		$postCMTcount->execute(array($pid));
		$postCMTcount = $postCMTcount->rowCount();
 
		if(!isset($_POST['all'])){
			$sql = $OP->dbh->prepare("SELECT * FROM `comments` WHERE `pid`=? ORDER BY `likes` DESC LIMIT 2");
		}else{
			$sql = $OP->dbh->prepare("SELECT * FROM `comments` WHERE `pid`=? ORDER BY `likes` DESC");
		}
		$sql->execute(array($pid));
 
 		$html = "<div class='comments' id='$pid'>";
			$html .= "<form class='cmt_form ajax_form' id='$pid' action='ajax/comment' success='Commented' error='Failed To Comment' while='Commenting'>";
				$html .= "<input type='hidden' id='clod' name='clod' value='0'/>";
				$html .= "<textarea name='cmt' type='text' class='textEditor' placeholder='Your Comment Here'></textarea>";
				$html .= "<input name='id' type='hidden' value='$pid'/>";
				$html .= "<input type='submit' value='Comment'/>";
			$html .= "</form>";
			if($sql->rowCount() != 0){
			 while( $r = $sql->fetch() ){
				$id		= $r['id'];
				$uid	= $r['uid'];
				$img	= get("avatar", $uid);
				$name	= get("name", $uid, false);
				$pLink	= get("plink", $uid, false);
				$lk		= $OP->didLike($id, "cmt") === false ? "Like":"Unlike";
				$class	= strtolower($lk) == "unlike" ? " unlike":"";
				$html .= "<div class='comment' id='$id'>";
					$html .= "<div class='left'>";
						$html .= "<img src='$img' class='pimg'/>";
					$html .= "</div>";
					$html .= "<div class='right'>";
						$html .= "<div class='top'>";
							$html .= "<a href='{$pLink}'>$name</a>";
							$html .= "<a class='time slink' href='" . HOST . "/view/{$r['pid']}#$id'>{$r['time']}</a>";
							$html .= "<div class='author_cmt_box'><div class='author_cmt_panel c_c'>";
								if($uid == $who){
									$html .= "<a class='de_cmt pointer' id='$id'>Delete Comment</a>";
								}
								$html .= "<a class='reply_cmt pointer' data-user='$uid' id='$pid'>Reply</a>";
							$html .= "</div></div>";
						$html .= "</div>";
						$html .= "<div class='cont'>";
							$html .= $OP->format($r['comment'], true);
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
			 if($postCMTcount>$sql->rowCount()){
				$html .= "<a class='load_more_comments pointer' id='$pid'>Load More Comments</a>";
			 }
			}else{
				$html .= "<h2>No Comments</h2>No one has posted a comment yet on this post.<br/>Be the first one to comment !";
			}
 		$html .= "</div>";
		return 	$html;
	}
	
	public static function notification($id){
		global $OP;
		
		$sql = $OP->dbh->prepare("SELECT * FROM `notify` WHERE id=?");
		$sql->execute(array($id));
		while($r = $sql->fetch()){
			$fid	= $r['fid'];
			$img	= get("avatar", $fid);
			$name	= get("fname", $fid, false);
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
				$nfs.="<div class='nfsi $iuR' id='$id' title='$iuT'>";
					$nfs.="<div class='left'>";
						$nfs.="<img height='32' width='32' src='$img'/>";
					$nfs.="</div>";
					$nfs.="<div class='right'>";
						$nfs.="<span class='name'>$name</span><br/>";
						$nfs.="<span class='time'>{$r['posted']}</span>";
						$nfs.="<div class='cont'>";
							$nfs .= $amsg;
						$nfs.="</div>";
					$nfs.="</div>";
				$nfs.="</div>";
			$nfs.="</a>";
		}
		$sql = $OP->dbh->prepare("UPDATE `notify` SET red='1' WHERE `id` = ?");
		$sql->execute(array($id));
		return $nfs;
	}
}
?>