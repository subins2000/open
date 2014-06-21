<?
/* This file works just like the get.php in JS directory (/inc/source/source/cdn/js/get.php) */
header("Content-type: text/css");
$requestedFiles=$_GET['f'];
if($requestedFiles!=""){
 $changed=false; /* Is any of the JS file changed from before ? */
 $mainDir = "./";
 $logFile = $mainDir."changed.txt"; /* File where CSS files' status are stored */
 $log     = file_get_contents($logFile);
 $log     = json_decode($log, true);
 $requestedFiless=explode(",", $requestedFiles); /* Becomes an array */
 foreach($requestedFiless as $v){
  $ltime=filemtime($mainDir."$v.css");
  if($ltime!=$log[$v]){
   $changed=true; /* Yes, a CSS file has been changed from before */
   $log[$v]=$ltime; /* Now, change it to the new time */
  }
 }
 arsort($log);
 $lkey=array_keys($log);
 $etag=hash("md5", $log[$lkey[0]]);
 header("ETag: $etag");
 header("Cache-Control: public");
 
 $_SERVER["HTTP_IF_NONE_MATCH"]=isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"]!=null ? $_SERVER["HTTP_IF_NONE_MATCH"]:01;
 if($changed===true || $_SERVER["HTTP_IF_NONE_MATCH"]!=$etag){
  foreach($requestedFiless as $v){
   echo file_get_contents($mainDir."$v.css");
  }
  $newLog=json_encode($log);
  file_put_contents($logFile, $newLog);
 }else{
  if($_SERVER["HTTP_IF_NONE_MATCH"]==$etag){
   header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified', true, 304);
  }
 }
}
?>
