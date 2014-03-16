<?
header("Content-type: text/css");
$f=$_GET['f'];
if($f!=""){
 $changed=false;
 $log=file_get_contents("changed.txt");
 $log=json_decode($log,true);
 $fs=explode(",",$f);
 foreach($fs as $v){
  $ltime=filemtime("$v.css");
  if($ltime!=$log[$v]){
   $changed=true;
   $log[$v]=$ltime;
   $ctime=$ltime;
  }
 }
 arsort($log);
 $lkey=array_keys($log);
 $etag=hash("md5",$log[$lkey[0]]);
 header("ETag: $etag");
 header("Cache-Control: public");
 
 $_SERVER["HTTP_IF_NONE_MATCH"]=isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"]!=null ? $_SERVER["HTTP_IF_NONE_MATCH"]:01;
 if($changed===true || $_SERVER["HTTP_IF_NONE_MATCH"]!=$etag){
  foreach($fs as $v){
   echo file_get_contents("$v.css");
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
