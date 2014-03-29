<?
include("post_rend.php");
if(isset($_POST['user']) && $_POST['user']!=''){
 $sql=$db->prepare("SELECT * FROM posts WHERE uid=:id AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 5");
 $sql->execute(array(":id"=>$_POST['user'],":who"=>$who));
}elseif(isset($_GET['q']) && $_GET['q']!="" && $_SERVER['PHP_SELF']=="/search.php"){
 $_GET['q']=urldecode($_GET['q']);
 $sql=$db->prepare("SELECT * FROM posts WHERE post LIKE :q AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 4");
 $sql->execute(array(":q"=>"%".$_GET['q']."%",":who"=>$who));
}elseif(isset($_GET['q']) && $_GET['q']=="" && $_SERVER['PHP_SELF']=="/search.php"){
 $sql=$db->prepare("SELECT * FROM posts WHERE privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))) ORDER BY id DESC LIMIT 10");
 $sql->execute(array(":who"=>$who));
}elseif(isset($_GET['id']) && $_GET['id']!="" && $_SERVER['PHP_SELF']=="/view.php"){
 $sql=$db->prepare("SELECT * FROM posts WHERE id=:id AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 1");
 $sql->execute(array(":who"=>$who,":id"=>$_GET['id']));
}else{
 $sql=$db->prepare("SELECT * FROM posts WHERE uid=:who OR uid IN (SELECT fid FROM conn WHERE uid=:who) AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 10");
 $sql->execute(array(":who"=>$who));
}
$tp=array();
while($r=$sql->fetch()){
 $k=$r['id'];
 $tp[$k]['uid']=$r['uid'];
 $tp[$k]['p']=$r['post'];
 $tp[$k]['time']=$r['posted'];
 $tp[$k]['privacy']=$r['privacy'];
 $tp[$k]['likes']=$r['likes'];
 $tp[$k]['cmt']=$r['cmts'];
 $tp[$k]['prs']=$tp[$k]['privacy'];
 $tp[$k]['pr']=$tp[$k]['privacy'];
 $k++;
}
echo show_posts($tp);
if($_SERVER['PHP_SELF']!="/view.php"){
 echo"<div class='load_more_posts'><div class='normal'>Load More Posts</div><div class='loader' hide><img src='http://open.subinsb.com/cdn/img/load.gif' height='32' width='32'/><span>Loading More Posts</span></div></div>";
}
?>
