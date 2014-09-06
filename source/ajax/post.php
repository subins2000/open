<?php
require_once "$docRoot/inc/render.php";
require_once "$docRoot/inc/class.social.php";
require_once "$docRoot/source/data/add.php";

$LS->init();
$privacy = isset($_POST['privacy']) ? $_POST['privacy']:"";

if($_P && ($privacy == "pub" || $privacy == "fri" || $privacy == "meo")){
 	$post = $_POST['post'];
 
 	/* Check if post contains any characters and not just white spaces */
 	if($post == '' || !preg_match("/[^\s]/", $post)){
  		/* Show a JSON Format Error Message */
  		$OP->ser("Post Can't be empty", "", "json");
 	}
 
 	$withoutMarkup = $OP->reverseMarkup($post);
 	$SocialPost    = new SocialPost($who);
 
 	/* Social Posting if requested */
 	if(isset($_POST['twitter'])){
  		$SocialPost->postToTwitter($withoutMarkup, $who);
 	}
 	if(isset($_POST['facebook'])){
  		$SocialPost->postToFacebook($withoutMarkup, $who, $privacy);
 	}
 	
 	$imgURL	= false;
 	/* Upload image if exists */
 	if(isset($_FILES['upload']) && $_FILES['upload']['tmp_name'] != ""){
		$imgURL = upload(curUser, false, $_FILES['upload']);
		if( $imgURL == "extensionNotSupported" ){
			$OP->ser("Image not supported", "The image you uploaded is not supported. Try another image", "json");
		}
	}
	
	$OP->format($post, true); /* Just For @mention notifications */
 	
 	$sql = $OP->dbh->prepare("INSERT INTO `posts` (`uid`, `post`, `image`, `time`, `privacy`) VALUES (?, ?, ?, NOW(), ?)");
 	$sql->execute(array($who, $post, $imgURL, $privacy));
 	
 	$sql = $OP->dbh->prepare("SELECT * FROM `posts` WHERE `uid` = ? ORDER BY `id` DESC LIMIT 1");
 	$sql->execute(array($who));
 	
 	$postsArr = $sql->fetchAll(PDO::FETCH_ASSOC);
 	$postID	 = $postsArr[0]['id'];
 	$OP->mentionNotify($postID, "post");
 	
 	$html = $OP->rendFilt(Render::post($postsArr));
 	header("Content-type: application/x-javascript");
?>
HTML = "<?php echo $html;?>";
if($(".post").length == 0){
 	$(".feed").html(HTML);
}else{
 	$(".post:first").before(HTML);
}
<?php
}
?>