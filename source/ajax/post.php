<?
require_once "$docRoot/inc/post_rend.php";
require_once "$docRoot/inc/reverse_markup.php";
require_once "$docRoot/inc/class.social.php";

$OP->init();
$privacy = isset($_POST['privacy']) ? $_POST['privacy']:"";

if($_P && ($privacy == "pub" || $privacy == "fri" || $privacy == "meo")){
 	$post = $_POST['post'];
 
 	/* Check if post contains any characters and not just white spaces */
 	if($post=='' || !preg_match("/[^\s]/", $post)){
  		/* Show a JSON Format Error Message */
  		$OP->ser("Post Can't be empty", "", "json");
 	}
 
 	$withoutMarkup = reverseMarkup($post);
 	$SocialPost    = new SocialPost($who);
 
 	/* Social Posting if requested */
 	if(isset($_POST['twitter'])){
  		$SocialPost->postToTwitter($withoutMarkup, $who);
 	}
 	if(isset($_POST['facebook'])){
  		$SocialPost->postToFacebook($withoutMarkup, $who, $privacy);
 	}
 
 	$OP->format($post, true); /* Just For @mention notifications */
 	$sql=$OP->dbh->prepare("INSERT INTO posts (`uid`, `post`, `time`, `privacy`) VALUES (?, ?, NOW(), ?)");
 	$sql->execute(array($who, $post, $privacy));
 	
 	$sql=$OP->dbh->prepare("SELECT * FROM posts WHERE `uid`=? ORDER BY id DESC LIMIT 1");
 	$sql->execute(array($who));
 	
 	$postsArr = $sql->fetchAll(PDO::FETCH_ASSOC);
 	$postID	 = $postsArr[0]['id'];
 	$OP->mentionNotify($postID, "post");
 	
 	$html = $OP->rendFilt(show_posts($postsArr));
?>
HTML = "<?echo $html;?>";
if($(".post").length == 0){
 	$(".feed").html(HTML);
}else{
 	$(".post:first").before(HTML);
}
<?
}
?>