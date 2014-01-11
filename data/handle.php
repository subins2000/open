<?
include("config.php");
$m=preg_match_all("/(.*?)\/(.*?)\z/",$_GET['request'],$f);
$u=$f[1][0];
$f=$f[2][0];
$avatar=0;
if($f=='' || $u==''){
 ser();
}else{
 if($f=="img/avatar"){
  include("resizer.php");
  $f="img";
  $avatar=1;
 }
 $sql=$db->prepare("SELECT * FROM data WHERE uid=? AND name=?");
 $sql->execute(array($u,$f));
 if($sql->rowCount()==0){ser();}
 while($r=$sql->fetch()){
  $t=$r['txt'];
 }
 header("Cache-Control: public");
 $etag=str_split($t,480);
 $etag=hash("sha256",$etag[0]);
 header("ETag: $etag");
 if($_SERVER["HTTP_IF_NONE_MATCH"]==$etag){
  header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified', true, 304);
 }else{
  header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK', true, 200);
 }
 header("Content-type:image/png");
 $t=base64_decode($t);
 if($avatar==1){
  $temp = tempnam("/tmp", "FOO");
  file_put_contents($temp,$t);
  $resize=new ResizeImage($temp);
  $resize->resizeTo(96,96,'exact');
  $resize->saveImage($temp,100);
  $t=file_get_contents($temp);
 }
 echo$t;
}
?>
