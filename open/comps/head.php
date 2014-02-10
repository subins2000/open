<?
include("$sroot/comps/config.php");
if(isset($t)){
 echo "<title>$t | Open - An Open Source Social Network</title>";
}else{
 echo "<title>Open - An Open Source Social Network</title>";
}
$cfs=$cfs=="" ? "main":"main,$cfs";
echo "<link rel='icon' href='http://open.subinsb.com/img/favicon.ico' />";
echo "<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>";
echo "<link type='text/css' rel='stylesheet' href='http://open.subinsb.com/css/get?f=$cfs' />";
if(!isset($fs)){
 $fs="main";
}else{
 $fs="main,$fs";
}
echo "<script src='http://open.subinsb.com/js/jquery.js'></script>";
echo "<script>".file_get_contents("$sroot/js/stats.js")."</script>";
echo "<script src='http://open.subinsb.com/js/get?f=$fs'></script>";
$theme=get("theme");
if($theme!=""){
 echo'<script>$(document).ready(function(){$("body").addClass("'.$theme.'");});</script>';
}
?>
