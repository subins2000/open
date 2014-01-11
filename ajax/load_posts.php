<?
include("config.php");
include("../comps/post_rend.php");
if($_POST['uid']==''){
 ch();
}
$id=filt($_POST['id']);
$usr=filt($_POST['uid']);
$q=filt(urldecode($_POST['q']));
if($id!=""){
 if($usr!=""){
  $sql=$db->prepare("SELECT * FROM posts WHERE id < :lid AND uid=:id AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":who"=>$who,":lid"=>$id,":id"=>$usr));
 }elseif($q!=""){
  $sql=$db->prepare("SELECT * FROM posts WHERE id < :lid  AND post LIKE :q AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":q"=>"%$q%",":who"=>$who,":lid"=>$id));
}elseif($q==""){
  $sql=$db->prepare("SELECT * FROM posts WHERE id < :lid AND privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":who"=>$who,":lid"=>$id));
}else{
  $sql=$db->prepare("SELECT * FROM posts WHERE id < :lid AND (uid=:who OR uid IN (SELECT fid FROM conn WHERE uid=:who) AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":who"=>$who,":lid"=>$id));
 }
 $cany=$sql->rowCount();
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
 $ht=str_replace("\n","<br/>",str_replace("/",'"+"/"+"',str_replace('"','\"',str_replace(">\n","",str_replace("\r","",show_posts($tp))))));
?>
localStorage['requested']=0;
<?if($cany==0){?>$(".load_more_posts").find(".normal").text("No More Posts To Show");<?}else{?>p="<?echo$ht;?>";$(".post:last").after(p);<?}?>$(".load_more_posts").find(".normal").show();$(".load_more_posts .loader").hide();
<?
}
?>
