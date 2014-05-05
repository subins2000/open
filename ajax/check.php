<?
Header("content-type: application/x-javascript");
include('config.php');
if(isset($_POST['u']) && $_POST['u']!=$who && $_POST['u']!="undefined"){
 echo "window.location.reload();"; /* Reload The Page */
 exit;
}
if($lg && $_P){
 if(isset($_POST['p']) && isset($_POST['pt']) && $_POST['p']!="undefined" && $_POST['pt']!="view"){
  /* Are There New Posts ? */
  include("../inc/post_rend.php");
  $id=$_POST['p'];
  $url=urldecode($_POST['cu']);
  $url=parse_url(str_replace("#", "%23", $url));
  $path=$url['path'];
  if($path=="/search"){
   parse_str($url['query'], $gets);
   $q=urldecode($gets['q']);
   $sql=$db->prepare("SELECT * FROM `posts` WHERE `id` > :lid AND `post` LIKE :q AND (`uid`=:who OR `uid` IN (SELECT `fid` FROM `conn` WHERE `uid`=:who) AND (`privacy`='pub' OR (`privacy`='fri' AND `uid` IN (SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (SELECT `uid` FROM `conn` WHERE `fid`=:who))))) ORDER BY `id` DESC LIMIT 10");
   $sql->execute(array(
    ":q"   => "%$q%",
    ":who" => $who,
    ":lid" => $id
   ));
  }elseif($_POST['pt']=="profile"){
   $pU=explode("/", $path);
   $pU=$pU[1];
   $sql=$db->prepare("SELECT * FROM `posts` WHERE `id` > :lid AND `uid`=:fid ORDER BY `id` DESC LIMIT 10");
   $sql->execute(array(
    ":fid" => $pU,
    ":lid" => $id
   ));
  }else{
   $sql=$db->prepare("SELECT * FROM posts WHERE `id` > :lid AND (`uid`=:who OR `uid` IN (SELECT `fid` FROM `conn` WHERE `uid`=:who) AND (`privacy`='pub' OR (`privacy`='fri' AND `uid` IN (SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (SELECT `uid` FROM `conn` WHERE `fid`=:who))))) ORDER BY `id` DESC LIMIT 10");
   $sql->execute(array(
    ":who" => $who,
    ":lid" => $id
   ));
  }
  if($sql->rowCount()!=0){
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
   $ht=rendFilt(show_posts($tp));
   $effect="";
   foreach($tp as $k=>$v){
    $effect.="$('#$k.post').hide().fadeIn(2000);";
   }
?>
   if($(".post:first").attr("id")!="<?echo$k;?>"){p="<?echo$ht;?>";$(".post:first").before(p);<?echo$effect;?>}
<?
  }
 }
 /* Are There New Notifications ?*/
 $sql=$db->prepare("SELECT red FROM notify WHERE red='0' AND uid=?");
 $sql->execute(array($who));
 $count=$sql->rowCount();
 if($count!=0){
?>
  $(".notifications #nfn_button").text("<?echo$count;?>");$(".notifications #nfn_button").addClass("b-red");
<? 
 }
 if(isset($_POST['fl'])){
  $requestedFile=$_POST['fl'];
  $_POST=$_POST[$_POST['fl']];
  if($requestedFile=="mC"){
   include("check_msg.php");
  }
 }
}else{
 header("Content-type: text/html");
 ser();
}
?>
