<?php
if(!loggedIn){
  $c = "?c=" . urlencode(\Fr\LS::curPageURL());
  if($_GET['service'] == "facebook"){
    \Fr\LS::redirect("/oauth/login_with_facebook" . $c);
  }else if($_GET['service'] == "google"){
    \Fr\LS::redirect("/oauth/login_with_google" . $c);
  }else{
    \Fr\LS::redirect("/login" . $c);
  }
}

require_once "$docRoot/inc/class.opth.php";
if(isset($_GET['service']) && isset($_GET['api_key']) && isset($_GET['scope']) && isset($_GET['redirect']) && isset($_GET['token'])){
  $_GET['service'] = $_GET['service'] == "" ? "open" : $_GET['service'];
  
  if($_GET['service'] == "facebook" || $_GET['service'] == "google" || $_GET['service'] == "open"){
    /**
     * Check if server exists and whether redirect URL is correct
     */
    $api_key = $_GET['api_key'];
    $redirect_url = urldecode($_GET['redirect']);
    $server = Opth::server($api_key, $redirect_url);
    $scope = explode(",", urldecode($_GET['scope']));
    
    if(count($scope) != count(array_intersect($scope, array_keys(Opth::$scopes)))){
      $error = array("Invalid Permissions", "The website you requested to log in to sent an invalid request : Invalid permissions.");
    }

    if($server === false){
      $OP->ser();
    }else{
      if(Opth::authorized()){
        $status = Opth::authorize($scope, $_GET['token']);
        
        if($status == "true"){
          $OP->redirect($redirect_url . "?opth_redirect=1&token={$_GET['token']}");
        }
      }
      
      $server_name = "<strong><a target='_blank' href='{$server['url']}'> {$server['title']}</a></strong>";
      
      if(isset($_POST['deny'])){
        $OP->redirect($redirect_url . "?status=error&error=denied");
      }else if(isset($_POST['authorize'])){
        Opth::authorize($scope, $_GET['token'], true);
        $OP->redirect($redirect_url . "?opth_redirect=1&token={$_GET['token']}");
      }
    }
  }else{
    $OP->ser();
  }
}else{
  $OP->ser();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Opth Login", "", "");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <?php
        if(!isset($error)){
        ?>
          <h1>Opth Login</h1>
          <p>Would you like to authorize <?php echo $server_name;?> to do these processes :</p>
          <ul>
            <?php
            foreach($scope as $item){
              echo "<li>". Opth::readable_scope($item) ."</li>";
            }
            ?>
          </ul>
          <div class="auth_buttons">
            <form method="POST" action="<?php echo \Fr\LS::curPageURL();?>">
              <button class="btn green" name="authorize">I Authorize</button>
            </form>
            <form method="POST" action="<?php echo \Fr\LS::curPageURL();?>">
              <button class="btn red" name="deny">No, I don't</button>
            </form>
          </div>
          <style>
          .auth_buttons{
            margin: 0px auto 10px;
            display: inline-block;
          }
          .auth_buttons form{
            display: inline-block;
          }
          </style>
          <p>Later, you can choose to deny any of the permissions <?php echo $server_name;?> currently asks.</p>
        <?php
        }else{
        ?>
          <h1>Opth Login - Error</h1>
        <?php
          $OP->ser($error[0], $error[1], "html", false);
          echo "<p><a href='{$server['url']}' class='btn'>Return To Site</a></p>";
        }
        ?>
      </div>
    </div>
  </body>
</html>
