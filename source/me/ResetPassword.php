<?php
\Fr\LS::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Reset Password - Manage Account");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <h2>Reset Password</h2>
        <?php
        $rePass = \Fr\LS::forgotPassword();
        if( $rePass == "resetPasswordForm" ){
          echo "<p>Enter your <strong>email</strong> in the field above (username is the email)</p>";
        }
        ?>
        <style>input[type=text], input[type=password]{width: 250px;}</style>
      </div>
    </div>
  </body>
</html>