<?
include("config.php");
include("../inc/post_rend.php");
include("../inc/reverse_markup.php");
include("../inc/post_to_tw.php");
include("../inc/post_to_fb.php");
ch();
$pr=$_POST['privacy'];
if($_P && ($pr=="pub" || $pr=="fri" || $pr=="meo")){
 $post=$_POST['post'];
 if($post=='' || !preg_match("/[^\s]/", $post)){
  jer("Post Can't be empty");
 }
 $rspst=reverseMarkup($post);
 if(isset($_POST['twitter'])){
  post_to_tw($rspst,$who);
 }
 if(isset($_POST['facebook'])){
  post_to_fb($rspst,$who,$pr);
 }
 filt($post, true); /* Just For @mention notifications */
 $sql=$db->prepare("INSERT INTO posts (`uid`, `post`, `posted`, `privacy`) VALUES (?, ?, NOW(), ?)");
 $sql->execute(array($who, $post, $pr));
 $sql=$db->prepare("SELECT * FROM posts WHERE `uid`=? ORDER BY id DESC LIMIT 1");
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
 sm_notify($k, "post");
 $ht=rendFilt(show_posts($tp));
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
