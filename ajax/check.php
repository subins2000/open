<?
Header("content-type: application/x-javascript");
include('config.php');
if(isset($_GET)){
 if($_GET['user']!=$who && $_GET['user']!="undefined"){
?>
window.location=window.location;
<?
 }
}
if($lg && $_GET['user']==$who && $_GET['fid']!="undefined" && $_GET['type']!="profile" && $_GET['type']!="view"){
 include("../comps/post_rend.php");
 $id=$_GET['fid'];
 $url=urldecode($_GET['url']);
 $url=parse_url(str_replace("#","%23",$url));
 $path=$url['path'];
 if($path=="/search"){
  parse_str($url['query'],$gets);
  $q=urldecode($gets['q']);
  $sql=$db->prepare("SELECT * FROM posts WHERE id > :lid AND post LIKE :q AND (uid=:who OR uid IN (SELECT fid FROM conn WHERE uid=:who) AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":q"=>"%$q%",":who"=>$who,":lid"=>$id));
 }else{
  $sql=$db->prepare("SELECT * FROM posts WHERE id > :lid AND (uid=:who OR uid IN (SELECT fid FROM conn WHERE uid=:who) AND (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))))) ORDER BY id DESC LIMIT 10");
  $sql->execute(array(":who"=>$who,":lid"=>$id));
 }
 if($sql->rowCount()==0){exit;}
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
 $effect="";
 foreach($tp as $k=>$v){
  $effect.="$('#$k.post').hide().fadeIn(2000);";
 }
?>
if($(".post:first").attr("id")!="<?echo$k;?>"){p="<?echo$ht;?>";$(".post:first").before(p);<?echo$effect;?>}
<?
}
?>
