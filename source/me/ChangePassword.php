<?php
\Fr\LS::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Change Password - Manage Account");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <h4>Change Password</h4>
        <div style="margin:0px auto;width: 60%;">
          <?php
          $status = \Fr\LS::changePassword();
          if( $status == "changePasswordForm" ){
            echo "<p>If you have created account with Facebook or Google, leave the current password blank</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>