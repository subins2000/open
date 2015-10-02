<?php \Fr\LS::init();?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Sign Up", "pc");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper"><div class="content" style="text-align:center;">
      <div id="social" style="<?php if(isset($_POST['submit']) || isset($_POST['verify'])){?>display:none;<?php }?>">
        <a class="button" href="oauth/login_with_facebook" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(<?php echo HOST;?>/cdn/img/fb_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
        <a class="button b-red" href="oauth/login_with_google" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(<?php echo HOST;?>/cdn/img/g+_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
      </div>
      <div id="classic">
        <?php
        $verified = 0;
        $code = isset($_POST['code']) ? $_POST['code']:"";
        if(isset($_POST['verify']) && $code!=""){
          $decoded = $OP->decrypt($code);
          $origSTR = "{$_POST['user']}cantMakePublic";
          if($origSTR == $decoded){
            $verified = 1;
          }else{
            $verified = 0;
          }
        }
        if($verified == 1){
        ?>
          <form action="register" method="POST" style="padding-left:15px;padding-top:1px;">
            <input name="user" value="<?php echo $code;?>" type="hidden" />
            <input name="nuser" value="<?php echo $_POST['user'];?>" type="hidden" />
            <h2>E-Mail</h2>
            <input type="text" style="width:290px;" value="<?php echo $_POST['user'];?>" disabled="disabled"/><br/>
            <h2>Password</h2>
            <input name="pass" style="width:290px;" id="pass" placeholder="Make It Great" autocomplete="off" type="password"/><br/>
            <h2>Retype Password</h2>
            <input name="pass2" style="width:290px;" id="pass2" placeholder="Is It that great ?" autocomplete="off" type="password"/><br/>
            <div id="ppbar" title="Strength"><div id="pbar"></div><div id="ppbartxt"></div></div>
            <h2>Name</h2>
            <input name="name" style="width:290px;" id="user" placeholder="You Must Have a Name" type="text"/><br/><cl/>
            <input type="submit" class="b-red" style="font-size: 20px;margin: 5px;" name="submit" value="Register Account" />
            <p>
              Already Have An Account ?
              <a href="<?php echo HOST;?>/login" class="button">Log In</a>
            </p>
          </form>
        <?php
        }elseif(!isset($_POST['verify']) && !isset($_POST['submit'])){
        ?>
          <form action="register" method="POST">
            <p>Type In Your E-Mail to Continue Signup Process.</p>
            <input name="mail" style="width:300px;" placeholder="Your E-Mail Please" type="text"/>
            <input type="submit" class="b-green" name="verify" value="Verify E-Mail"/>
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
            $OP->ser("You Already Have An Account!", "There is already an account registered with the E-Mail you have given. <a href='http://open.subinsb.com/me/ResetPassword'>Forgot Password ?</a>");
          }
          $secretToken = $OP->encrypt("{$email}cantMakePublic");
          if(CLEAN_HOST == "open.dev"){
            echo $secretToken;
          }
          
          if( $OP->sendEMail($email, "Verify Your E-Mail", "You requested for registering on Open. For signing up, you need to verify your E-Mail address. Paste the code below in the input field of the page where you requested for signing up.<blockquote>{$secretToken}</blockquote>") !== false ) {
        ?>
            <p>An E-Mail containing a code have been sent to the E-Mail address you gave us. Check Your Inbox for that mail. The mail might have went to the SPAM folder. Hence you have to check that folder too.</p><cl/>
            <form action="register" method="POST">
              <p>Paste The Code you received via E-Mail below</p><cl/>
              <input name="user" value="<?php echo $email;?>" type="hidden"/>
              <input name="code" style="width:290px;" autocomplete="off" placeholder="Paste The Code Here" type="text"/><br/><cl/>
              <input name="verify" type="submit" value="Complete Verification"/><cl/>
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
          $origSTR = "{$_POST['nuser']}cantMakePublic";
          
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
    </div></div>
    <style>#ppbar{background:#CCC;width:400px;height:20px;margin: 5px auto;position: relative;}#pbar{margin:0px;width:0px;background:lightgreen;height: 100%;}#ppbartxt{text-align: right;position: absolute;right: 5px;top: 1px;}</style>
    <?php include "$docRoot/inc/footer.php";?>
  </body>
</html>
