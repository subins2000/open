<?php
require_once "$docRoot/inc/render.php";
if(isset($_POST['uid']) && $_POST['uid']==''){
	$LS->init();
}
$lastPID     = isset($_POST['id']) ? $OP->format($_POST['id']) : ""; /* The last Post ID */
$profileUser = isset($_POST['uid']) ? $OP->format($_POST['uid']) : ""; /* The Profile ID */
$searchQuery = isset($_POST['q']) ? $OP->format(urldecode($_POST['q'])) : ""; /* In Search Pages, it's query */

if($lastPID != ""){
 	if($profileUser != ""){
  		$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE id < :lid AND uid=:id AND (
   		privacy='pub' OR (
   			privacy='fri' AND uid IN (
   				SELECT fid FROM conn WHERE uid=:who AND fid IN (
   					SELECT uid FROM conn WHERE fid=:who
  		 			)
   			)
	   	)
  		) ORDER BY id DESC LIMIT 10");
  		$sql->execute(array(":who" => $who, ":lid" => $lastPID, ":id" => $profileUser));
 	}elseif($searchQuery != ""){
  		$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE id < :lid  AND post LIKE :q AND (
  			privacy='pub' OR (
  				privacy='fri' AND uid IN (
  					SELECT fid FROM conn WHERE uid=:who AND fid IN (
  						SELECT uid FROM conn WHERE fid=:who
  					)
  				)
  			)
  		) ORDER BY id DESC LIMIT 10");
  		$sql->execute(array(":q" => "%$searchQuery%", ":who" => $who, ":lid" => $lastPID));
	}elseif($searchQuery == "" && isset($_POST['q'])){
  		$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE id < :lid AND privacy='pub' OR (
  			privacy='fri' AND uid IN (
  				SELECT fid FROM conn WHERE uid=:who AND fid IN (
  					SELECT uid FROM conn WHERE fid=:who
  				)
  			)
  		) ORDER BY id DESC LIMIT 10");
  		$sql->execute(array(":who" => $who, ":lid" => $lastPID));
	}else{
  		$sql=$OP->dbh->prepare("SELECT * FROM `posts` WHERE `id` < :lid AND (
  			`uid`=:who OR `uid` IN (
  				SELECT `fid` FROM `conn` WHERE `uid`=:who
  			) AND (
  				`privacy`='pub' OR (
  					`privacy`='fri' AND `uid` IN (
  						SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (
  							SELECT `uid` FROM `conn` WHERE `fid`=:who
  						)
  					)
  				)
  			)
  		) ORDER BY `id` DESC LIMIT 10");
  		$sql->execute(array(":who" => $who, ":lid" => $lastPID));
 	}
 	$postCount = $sql->rowCount();
 	$postsArr  = $sql->fetchAll(PDO::FETCH_ASSOC);
 	$html = $OP->rendFilt(Render::post($postsArr));
?>
	<?php
	if($postCount == 0){
		echo '$(".load_more_posts").find(".normal").text("No More Posts To Show");';
	}else{
	?>
		$(".post:last").after("<?php echo $html;?>");
	<?php
	}
	echo '$(".load_more_posts").find(".normal").show();';
}
echo 'localStorage["requested"] = 0;';
echo '$(".load_more_posts .loader").hide();';
echo '$(".load_more_posts").find(".normal").text("No More Posts To Show").show();';
?>