<?

$OP->inc("inc/post_rend.php");
$OP->inc("inc/reverse_markup.php");
$OP->inc("inc/post_to_tw.php");
$OP->inc("inc/post_to_fb.php");
$OP->init();
$pr=$_POST['privacy'];
if($_P && ($pr=="pub" || $pr=="fri" || $pr=="meo")){
 $post=$_POST['post'];
 if($post=='' || !preg_match("/[^\s]/", $post)){
  $OP->ser("Post Can't be empty", "", "json");
 }
 $rspst=reverseMarkup($post);
 if(isset($_POST['twitter'])){
  post_to_tw($rspst, $who);
 }
 if(isset($_POST['facebook'])){
  post_to_fb($rspst, $who, $pr);
 }
 $OP->format($post, true); /* Just For @mention notifications */
 $sql=$OP->dbh->prepare("INSERT INTO posts (`uid`, `post`, `posted`, `privacy`) VALUES (?, ?, NOW(), ?)");
 $sql->execute(array($who, $post, $pr));
 $sql=$OP->dbh->prepare("SELECT * FROM posts WHERE `uid`=? ORDER BY id DESC LIMIT 1");
 $sql->execute(array($who));
 $tp=array();
 while($r=$sql->fetch()){
  $k=$r['id'];
  $tp[$k]['uid']=$r['uid'];
  $tp[$k]['p']=$r['post'];
  $tp[$k]['time']=$r['posted'];
  $tp[$k]['likes']=$r['likes'];
  $tp[$k]['cmt']=$r['cmts'];
  $tp[$k]['prs']=$r['privacy'];
 }
 $OP->mentionNotify($k, "post");
 $ht=$OP->rendFilt(show_posts($tp));
?>
p="<?echo$ht;?>";
if($(".post").length==0){
 $(".feed").html(p);
}else{
 $(".post:first").before(p);
}
<?
}
?>
