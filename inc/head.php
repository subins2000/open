<?
if(isset($t)){
 echo "<title>$t | Open - An Open Source Social Network</title>";
}else{
 echo "<title>Open - An Open Source Social Network</title>";
}
$cfs=isset($cfs) ? $cfs:"";
$cfs=$cfs=="" ? "main":"main,$cfs";
echo "<link rel='icon' href='http://open.subinsb.com/cdn/img/favicon.ico' />";
echo "<link async='async' href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>";
echo "<link async='async' type='text/css' rel='stylesheet' href='http://open.subinsb.com/cdn/css/get?f=$cfs' />";
if(!isset($fs)){
 $fs="main";
}else{
 $fs="main,$fs";
}
echo "<script src='http://open.subinsb.com/cdn/js/jquery.js'></script>";
if(isset($sroot) && isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI']!="/me/ResetPassword" && $_SERVER['HTTP_HOST']!="open.subinsb.com"){
 echo "<script>".file_get_contents("$sroot/cdn/js/stats.js")."</script>";
}
echo "<script async='async' src='http://open.subinsb.com/cdn/js/get?f=$fs'></script>";
?>
