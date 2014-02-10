<?
$url=$_GET['url'];
$url=rawurldecode($url);
header("Location: $url");
?>
