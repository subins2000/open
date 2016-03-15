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
    $error = 'Bad E-Mail/Password';
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
      <div class="content row">
        <div class="col s6">
          <h4>Log In</h4>
          <?php
          if(isset($error)){
            $OP->ser($error, "", "html", false);
          }
          ?>
          <form action="login?c=<?php echo urlencode($returnURL);?>" method="POST">
            <div class="input-field">
              E-Mail<br/>
              <input name="user" type="text" size="30"/>
            </div>
            <div class="input-field">
              Password<br/>
              <input name="pass" autocomplete="off" type="password" size="30"/>
            </div>
            <div class="input-field">
              <input type="checkbox" name="remember_me" id="remember_me" />
              <label for="remember_me">Remember Me</label>
            </div>
            <div class="input-field">
              <button name="submit" class="btn">Log In</button>
            </div>
          </form>
          <div class="input-field">
            <a href="<?php echo O_URL ;?>/register" class="btn blue">Register</a>
            <a href="<?php echo O_URL ;?>/me/ResetPassword" class="btn red">Forgot Password ?</a>
          </div>
        </div>
        <div class="col s6">
          <h4>Social Log In</h4>
          <a class="btn" href="oauth/login_with_facebook?c=<?php echo urlencode($returnURL);?>" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(<?php echo O_URL ;?>/cdn/img/fb_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
          <a class="btn red" href="oauth/login_with_google?c=<?php echo urlencode($returnURL);?>" style="display: inline-block;height: 43px;margin: 10px 0;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(<?php echo O_URL ;?>/cdn/img/g+_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
        </div>
      </div>
      <?php include "$docRoot/inc/footer.php";?>
    </div>
  </body>
</html>
