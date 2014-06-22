<?
include("post_rend.php");
if(isset($_POST['user']) && $_POST['user']!=''){
 	$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE uid=:id AND (
 		privacy='pub' OR (
 			privacy='fri' AND uid IN (
 				SELECT fid FROM conn WHERE uid=:who AND fid IN (
 					SELECT uid FROM conn WHERE fid=:who
 				)
 			)
 		)
 	) ORDER BY id DESC LIMIT 5");
 	$sql->execute(array(
 	 	":id" => $_POST['user'],
 	 	":who" => $who
 	));
}elseif(isset($_GET['q']) && $_GET['q']!="" && $_SERVER['PHP_SELF']=="/search.php"){
 	$_GET['q']=urldecode($_GET['q']);
 	$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE post LIKE :q AND (
 		privacy='pub' OR (
 			privacy='fri' AND uid IN (
 				SELECT fid FROM conn WHERE uid=:who AND fid IN (
 					SELECT uid FROM conn WHERE fid=:who
 				)
 			)
 		)
 	) ORDER BY id DESC LIMIT 4");
 	$sql->execute(array(
 		":q" => "%".$_GET['q']."%",
 		":who" => $who
 	));
}elseif(isset($_GET['q']) && $_GET['q']=="" && $_SERVER['PHP_SELF']=="/search.php"){
 	$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE privacy='pub' OR (
 		privacy='fri' AND uid IN (
 			SELECT fid FROM conn WHERE uid=:who AND fid IN (
 				SELECT uid FROM conn WHERE fid=:who
 			)
 		)
 	) ORDER BY id DESC LIMIT 10");
 	$sql->execute(array(
 		":who" => $who
 	));
}elseif(isset($_GET['id']) && $_GET['id']!="" && $_SERVER['PHP_SELF']=="/view.php"){
 	$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE id=:id AND (
 		privacy='pub' OR (
 			privacy='fri' AND uid IN (
 				SELECT fid FROM conn WHERE uid=:who AND fid IN (
 					SELECT uid FROM conn WHERE fid=:who
 				)
 			)
 		)
 	) ORDER BY id DESC LIMIT 1");
 	$sql->execute(array(
 		":who" => $who,
 		":id" => $_GET['id']
 	));
}else{
 	$sql = $OP->dbh->prepare("SELECT * FROM posts WHERE uid=:who OR uid IN (
 		SELECT fid FROM conn WHERE uid=:who
 	) AND (
 		privacy='pub' OR (
 			privacy='fri' AND uid IN (
 				SELECT fid FROM conn WHERE uid=:who AND fid IN (
 					SELECT uid FROM conn WHERE fid=:who
 				)
 			)
 		)
 	) ORDER BY id DESC LIMIT 10");
 	$sql->execute(array(":who" => $who));
}

$postArr = $sql->fetchAll(PDO::FETCH_ASSOC);
echo show_posts($postArr);

if($_SERVER['PHP_SELF']!="/view.php"){
 	echo "<div class='load_more_posts'><div class='normal'>Load More Posts</div><div class='loader' hide><img src='" . HOST . "/source/cdn/img/load.gif' height='32' width='32'/><span>Loading More Posts</span></div></div>";
}
?>