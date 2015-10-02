<?php
$_GET['c'] = isset($_GET['c']) ? $_GET['c'] : "";
$returnURL = urldecode($_GET['c']);
$returnURL = $returnURL == "" ? "/home" : $returnURL;

if(isset($_POST['submit'])){
  $user = $OP->format($_POST['user']);
  $pass = $OP->format($_POST['pass']);
  if( $pass != "" && \Fr\LS::login($user, $pass, isset($_POST['remember_me'])) ){
    header("Location: $returnURL");
    exit;
  }else{
    $error = 'E-Mail/Password is Incorrect';
  }
}
if(isset($_GET['logout']) && $_GET['logout'] == "true"){
  \Fr\LS::logout();
}elseif(loggedIn){
  header("Location: $returnURL;");
  exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Log In");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content blocks" style="text-align:center;">
        <div class="block" style="width:300px;padding: 0px 10px;text-align:left;">
          <h1>Log In</h1>
          <form action="login?c=<?php echo urlencode($returnURL);?>" method="POST">
            <div style="margin:5px 0px;">
              E-Mail<br/>
              <input name="user" type="text" size="30"/>
            </div>
            <div>
              Password<br/>
              <input name="pass" autocomplete="off" type="password" size="30"/>
            </div><cl/>
            <label class="blocks">
              <input type="checkbox" class="block" name="remember_me"/>
              <span class="block">Remember Me</span>
            </label><cl/>
            <div>
              <input name="submit" type="submit" value="Log In"/>
              <a href="<?php echo HOST;?>/register" class="button b-green">Register</a>
            </div><cl/>
            <a href="<?php echo HOST;?>/me/ResetPassword" class="button b-red">Forgot Password ?</a>
          </form>
          <?php
          if(isset($error)){
            $OP->ser($error, "", "html", false);
          }
          ?>
        </div>
        <div class="block" style="width:200px;text-align:left;">
          <h1>Social Log In</h1>
          <a href="<?php echo HOST;?>/oauth/login_with_facebook?c=<?php echo $returnURL;?>"><img src="<?php echo HOST;?>/cdn/img/fb_login.png"/></a><cl/>
          <a href="<?php echo HOST;?>/oauth/login_with_google?c=<?php echo $returnURL;?>"><img src="<?php echo HOST;?>/cdn/img/google_login.png"/></a>
        </div>
      </div>
    </div>
    <?php include "$docRoot/inc/footer.php";?>
  </body>
</html>
