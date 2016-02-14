<?php
require_once "$docRoot/inc/class.opth.php";

if(isset($_POST['api_key']) && isset($_POST['api_secret']) && isset($_POST['token'])){
  $api_key = $_POST['api_key'];
  $api_secret = $_POST['api_secret'];
  $token = $_POST['token'];
  
  $sid = Opth::exists($api_key, $api_secret);
  if($sid !== false){
    $sql = $OP->dbh->prepare("SELECT `access_token` FROM `opth_session` WHERE `server_token` = ? AND `sid` = ?");
    $sql->execute(array($token, $sid));
      
    if($sql->rowCount() == 0){
      echo "false";
    }else{
      echo $sql->fetchColumn();
      $sql = $OP->dbh->prepare("DELETE FROM `opth_tokens` WHERE `sid` = ? AND `token` = ?");
      $sql->execute(array($sid, $token));
    }
  }else{
    echo "false";
  }
}
