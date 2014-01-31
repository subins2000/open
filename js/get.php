<?
header("Content-type: application/x-javascript");
$f=$_GET['f'];
if($f!=""){
 $changed=false;
 $itc=0;
 $log=file_get_contents("changed.txt");
 $log=json_decode($log,true);
 $fs=explode(",",$f);
 foreach($fs as $v){
  $ltime=filemtime("$v.js");
  if($ltime!=$log[$v]){
   $changed=true;
   $log[$v]=$ltime;
   $ctime=$ltime;
  }
 }
 arsort($log);
 $etag=hash("md5",$log[0]);
 header("ETag: $etag");
 if($changed===true || $_SERVER["HTTP_IF_NONE_MATCH"]!=$etag){
  foreach($fs as $v){
   $itc++;
   if($itc==1){
    echo "$(document).ready(function(){";
   }
   echo file_get_contents("$v.js");
   if(count($fs)==$itc){
    echo "});";
   }
  }
  $clog=json_encode($log);
  file_put_contents("changed.txt", $clog);
 }else{
  if($_SERVER["HTTP_IF_NONE_MATCH"]==$etag){
   header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified', true, 304);
  }
 }
}
?>
