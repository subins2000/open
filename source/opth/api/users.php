<?php
require_once "$docRoot/inc/class.opth.php";

if(isset($_POST['api_key']) && isset($_POST['api_secret']) && isset($user_token) && isset($what)){
  $sid = Opth::exists($_POST['api_key'], $_POST['api_secret']);
  if($sid == false){
    echo "false";
    exit;
  }
  
  Opth::$sid = $sid;
  if(Opth::authorized($user_token) == false){
    echo "false";
    exit;
  }
  $sql = $OP->dbh->prepare("SELECT `uid`, `permissions` FROM `opth_session` WHERE `access_token` = ? AND `sid` = ?");
  $sql->execute(array($user_token, $sid));
  $data = $sql->fetch(PDO::FETCH_ASSOC);
  
  $uid = $data['uid'];
  $given_scopes = array_flip(unserialize($data['permissions']));
  
  $scope_to_values = array(
    "read-name" => "name"
  );
  
  $obtainable_values = array(
    "info" => array(
      "read-name"
    ),
    "email" => array(
      "email-send"
    )
  );

  if(substr($what, 0, 7) == "action-"){
    $what = substr_replace($what, "", 0, 7);
    
    if(isset($obtainable_values[$what])){
      if($what == "email" && isset($given_scopes[$obtainable_values[$what][0]]) && isset($_POST['subject']) && isset($_POST['body']) && $_POST['subject'] != null  && $_POST['body'] != null){
        $sql = $OP->dbh->prepare("SELECT `username` FROM `users` WHERE `id` = ?");
        $sql->execute(array($uid));
        
        $email = $sql->fetchColumn();
        $status = $OP->sendEMail($email, $_POST['subject'], $_POST['body'], true);
        echo $status == true ? "true" : "false";
      }else{
        echo "false";
      }
    }else{
      echo "false";
    }
  }else{
    if(isset($obtainable_values[$what])){
      $output = array();
      $sql = $OP->dbh->prepare("SELECT * FROM `users` WHERE `id` = ?");
      $sql->execute(array($uid));
      $user_info = $sql->fetch(PDO::FETCH_ASSOC);
    
      foreach($obtainable_values[$what] as $scope){
        if(isset($given_scopes[$scope])){
          $scope_key = $scope_to_values[$scope];
          
          $output[$scope_key] = $user_info[$scope_key];
        }
      }
      echo json_encode($output);
    }else{
      echo "false";
    }
  }
}else{
  echo "false";
}
