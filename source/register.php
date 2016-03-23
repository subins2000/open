<?php
\Fr\LS::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Sign Up", "pc");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content" style="text-align:center;">
        <h3>Create An Account</h3>
        <div id="social" style="<?php if(isset($_POST['submit']) || isset($_POST['verify'])){?>display:none;<?php }?>">
          <a class="btn" href="oauth/login_with_facebook" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(<?php echo O_URL ;?>/cdn/img/fb_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
          <a class="btn red" href="oauth/login_with_google" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(<?php echo O_URL ;?>/cdn/img/g+_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
        </div>
        <div id="classic" class="input-field">
          <?php
          $verified = 0;
          $code = isset($_POST['code']) ? $_POST['code']:"";
          if(isset($_POST['verify']) && $code!=""){
            $decoded = $OP->decrypt($code);
            $origSTR = $_POST['user'] . $cfg['keys']['email_verification'];
            if($origSTR == $decoded){
              $verified = 1;
            }else{
              $verified = 0;
            }
          }
          if($verified == 1){
          ?>
            <form action="register" method="POST" style="text-align: left;">
              <input name="user" value="<?php echo $code;?>" type="hidden" />
              <input name="nuser" value="<?php echo $_POST['user'];?>" type="hidden" />
              <div class="input-field row">
                <i class="material-icons prefix">email</i>
                <input type="text" value="<?php echo $_POST['user'];?>" disabled="disabled"/>
                <label>E-Mail</label>
              </div>
              <div class="input-field row">
                <i class="material-icons prefix">account_circle</i>
                <input name="name" id="user" type="text"/>
                <label for="user">Full Name</label>
              </div>
              <div class="row">
                <div class="input-field col m6">
                  <input name="pass" id="pass" type="password"/>
                  <label for="pass">Password</label>
                </div>
                <div class="input-field col m6">
                  <input name="pass2" id="pass2" type="password"/>
                  <label for="pass2">Retype Password</label>
                </div>
              </div>
              <div class="row">
                <i class="material-icons col s1">security</i>
                <div id="ppbar" class="progress col m11" title="Strength"><div class="determinate" id="pbar"></div><div id="ppbartxt"></div></div>
              </div>
              <button class="btn red btn-large" style="font-size: 20px;margin: 5px;" name="submit">Register Account</button>
            </form>
            <style>#ppbar{height:20px;margin: 5px auto;position: relative;}#ppbartxt{text-align: right;position: absolute;right: 5px;top: 1px;}</style>
          <?php
          }elseif(!isset($_POST['verify']) && !isset($_POST['submit'])){
          ?>
            <form action="register" method="POST">
              <p>Type In Your E-Mail to Continue Signup Process.</p>
              <input name="mail" style="width:300px;" placeholder="Your E-Mail Please" type="text"/>
              <button class="btn green" name="verify">Verify E-Mail</button>
              <p>You can only sign up if you verify your email.</p>
            </form>
          <?php
          }elseif($verified == 0 && isset($_POST['verify']) && $code!=""){
            $OP->ser("Wrong Verification Code", "The Code you entered is wrong.");
          }
          if( isset($_POST['verify']) && !isset($_POST['code']) ){
            $email = $_POST['mail'];
            if(!\Fr\LS::validEmail($email)){
              $OP->ser("E-Mail Is Not Valid", "The E-Mail you submitted is not a valid E-Mail");
            }
            if( \Fr\LS::userExists($email) ){
              $OP->ser("You Already Have An Account!", "There is already an account registered with the E-Mail you have given.<cl/><a href='". O_URL ."/me/ResetPassword' class='btn red'>Forgot Password ?</a>");
            }
            $secretToken = $OP->encrypt($email . $cfg['keys']['email_verification']);
            if(CLEAN_HOST == "open.sim"){
              echo $secretToken;
            }
            
            if( $OP->sendEMail($email, "Verify Your E-Mail", "You requested for registering on Open. For signing up, you need to verify your E-Mail address. Paste the code below in the input field of the page where you requested for signing up.<blockquote>{$secretToken}</blockquote>") !== false ) {
          ?>
              <p>An E-Mail containing a code have been sent to the E-Mail address you gave us. Check Your Inbox for that mail. The mail might have went to the SPAM folder. Hence you have to check that folder too.</p><cl/>
              <form action="register" method="POST">
                <p>Paste The Code you received via E-Mail below</p><cl/>
                <input name="user" value="<?php echo $email;?>" type="hidden"/>
                <input name="code" style="width:290px;" autocomplete="off" placeholder="Paste The Code Here" type="text"/><br/><cl/>
                <button name="verify" class="btn green">Complete Verification</button><cl/>
              </form>
          <?php
            }else{
              $OP->ser("Error", "Something happenned while sending verification code. Try again for atleast 6 times and if it didn't work, post the issue on <a href='https://github.com/subins2000/open/issues'>GitHub</a>");
            }
          }
          if(isset($_POST['submit'])){
            $email = strtolower($OP->format($_POST['nuser']));
            $pass = $OP->format($_POST['pass']);
            $pass2 = $OP->format($_POST['pass2']);
            $name = $OP->format($_POST['name']);
            
            $decoded = $OP->decrypt($_POST['user']);
            $origSTR = $_POST['nuser'] . $cfg['keys']['email_verification'];
            
            if($origSTR != $decoded){
              $OP->ser("User Not Verified.", "The user in which this form was sent have not verified his/her E-Mail.");
            }
            if($email == "" || $pass == '' || $pass2 == '' || $name == ''){
              $OP->ser("Fields Left Blank", "Some Fields were left blank. Please fill up all fields. You now have to start over the signup process.");
            }
            if( !ctype_alnum(strtolower(str_replace(" ", "", $name))) ){
              $OP->ser("Invalid Name", "The Name is not valid. Only ALPHANUMERIC characters are allowed.");
            }
            if($pass != $pass2){
              $OP->ser("Passwords Don't Match", "The Passwords you entered didn't match");
            }
            $json = '{"joined":"'.date("Y-m-d H:i:s").'"}';
            
            \Fr\LS::register($email, $pass, array(
              "name" => $name,
              "udata" => $json,
              "seen" => ""
            ));
            
            $OP->sss("Registration Success", "Your account has been created. Log In <a href='login'>here</a>");
          }
          ?>
        </div>
      </div>
      <?php include "$docRoot/inc/footer.php";?>
    </div>
  </body>
</html>
