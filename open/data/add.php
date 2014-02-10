<?
include("config.php");
ch();
include("resizer.php");
function upload($u,$n,$f){
 global$db;
 $ext=explode(".",$f['name']);
 $cex=count($ext)-1;
 $ext=strtolower($ext[$cex]);
 $exts=array("png","jpg","gif","jpeg");
 if($u!="" && is_array($f) && count($f)!=0 && array_search($ext,$exts)!==false){
  $path=$f['tmp_name'];
  $resize=new ResizeImage($path);
  if($resize->imgw()>500 || $resize->imgh()>500){
   $total=$resize->imgw()+$resize->imgh();
   $nw=$resize->imgw()/$total*700;
   $nh=$resize->imgh()/$total*700;
  }else{
   $nw=$resize->imgw();
   $nh=$resize->imgh();
  }
  $resize->resizeTo($nw,$nh,'exact');
  $resize->saveImage($path,50);
  $d=file_get_contents($path);
  $d=base64_encode($d);
  $sql=$db->prepare("SELECT * FROM data WHERE uid=? AND name=?");
  $sql->execute(array($u,$n));
  if($sql->rowCount()!=0){
   $sql=$db->prepare("UPDATE data SET txt=? WHERE uid=? AND name=?");
   $sql->execute(array($d,$u,$n));
  }else{
   $sql=$db->prepare("INSERT INTO data (uid,name,txt) VALUES (?,?,?)");
   $sql->execute(array($u,$n,$d));
  }
  return "http://open.subinsb.com/data/$u/$n";
 }else{
  return false;
 }
}
?>
