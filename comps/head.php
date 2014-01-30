<?
include("$sroot/comps/config.php");
echo "<style>";
echo file_get_contents("$sroot/css/main.css");
if(isset($cfs)){
 $cfs=explode(",",$cfs);
 foreach($cfs as $v){
  echo file_get_contents("$sroot/css/$v.css");
 }
}
echo "</style>";
if(isset($t)){
 echo "<title>$t | Open - An Open Source Social Network</title>";
}else{
 echo "<title>Open - An Open Source Social Network</title>";
}
if(!isset($fs)){
 $fs="main";
}else{
 $fs="main,$fs";
}
echo"<script>";
 echo file_get_contents("$sroot/js/jquery.js").";";
 $fs=explode(",",$fs);
 $al_d_ready=false;
 $itc=0;
 foreach($fs as $v){
  $itc++;
  if(!$al_d_ready){
   echo "$(document).ready(function(){";
   $al_d_ready=true;
  }
  echo file_get_contents("$sroot/js/$v.js");
  if(count($fs)==$itc){
   $theme=get("theme");
   if($theme!=""){
    echo"$('body').addClass('$theme');";
   }
   echo "});";
   echo file_get_contents("$sroot/js/stats.js");
  }
 }
echo"</script>";
?>
