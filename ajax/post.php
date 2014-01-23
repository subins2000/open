<?
include("config.php");
include("../comps/post_rend.php");
include("../comps/reverse_markup.php");
include("../comps/post_to_tw.php");
include("../comps/post_to_fb.php");
ch();
$pr=$_POST['privacy'];
if($_P && ($pr=="pub" || $pr=="fri" || $pr=="meo")){
 if($_POST['post']==''){jer("Post Can't be empty");}
 $rspst=reverseMarkup($_POST['post']);
 if(isset($_POST['twitter'])){
  post_to_tw($rspst,$who);
 }
 if(isset($_POST['facebook'])){
  post_to_fb($rspst,$who,$pr);
 }
 $p=filt($_POST['post'],true);
 $sql=$db->prepare("INSERT INTO posts (uid,post,posted,privacy) VALUES (?,?,NOW(),?)");
 $sql->execute(array($who,$p,$pr));
 $sql=$db->prepare("SELECT * FROM posts WHERE uid=? ORDER BY id DESC LIMIT 1");
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
 sm_notify($k);
 $ht=str_replace("\n","<br/>",str_replace('"','\"',str_replace("\r","",show_posts($tp))));
?>
p="<?echo$ht;?>";if($(".post").length==0){$(".feed").html(p);}else{$(".post:first").before(p);}
<?
}
?>
