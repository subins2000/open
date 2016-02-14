<?php
require_once "$docRoot/inc/class.opth.php";

if(isset($_POST['api_key']) && isset($_POST['api_secret']) && isset($_POST['token']) && strlen($_POST['token']) == 20){
  $api_key = $_POST['api_key'];
  $api_secret = $_POST['api_secret'];
  $token = $_POST['token'];
  
  $sid = Opth::exists($api_key, $api_secret);
  if($sid !== false){
    $sql = $OP->dbh->prepare("SELECT COUNT(1) FROM `opth_tokens` WHERE `sid` = ? AND `token` = ?");
    $sql->execute(array($sid, $token));
    
    if($sql->fetchColumn() == 0){
      $sql = $OP->dbh->prepare("INSERT INTO `opth_tokens` VALUES (?, ?)");
      $sql->execute(array($sid, $token));
    }
    echo "true";
  }else{
    echo "false";
  }
}else{
  echo "false";
}
