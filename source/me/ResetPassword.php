<??>
<!DOCTYPE html><html><head>
<?$t="Reset Password - Manage Account";$OP->inc("inc/head.php");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content">
  <h2>Reset Password</h2>
  Fill up the following form to reset your password.<br/><cl/>
  <form method="POST" action="ResetPassword">
   <table style="margin:0px auto;"><tbody>
    <tr><td>Email</td><td>:</td><td><input type="text" placeholder="example@example.com" size="30" name="mail"></td></tr>
    <tr><td></td><td></td><td><input type="submit"></td></tr>
   </tbody></table>
  </form>
  <?
  if(isset($_POST['mail']) && $_POST['mail']!=''){
   $m=$_POST['mail'];
   $sql=$OP->dbh->prepare("SELECT id FROM users WHERE username=?");
   $sql->execute(array($m));
   while($r=$sql->fetch()){
    $u=$r['id'];
   }
   if($sql->rowCount()==0){
    $OP->ser("Are you Real ?", "No user has been registered with the E-Mail you gave.");
   }
   $rand=$OP->randStr(35);
   $sql=$OP->dbh->prepare("INSERT INTO verify(uid, code, posted) VALUES(?, ?, NOW())");
   $sql->execute(array($u, $rand));
   $rand=urlencode($rand);
   $OP->sendEMail($m, "Reset Password", "You have requested to reset your password. Click the following link to reset your password. Note that this link is a one click link which means that the link will not work adnymore once visited.<blockquote><a href='" . HOST . "/me/ConfirmPasswordReset?id=$rand'>" . HOST . "/me/ConfirmPasswordReset?id=$rand</a></blockquote>If you didn't request to reset your password, ignore this mail. Some guy is trying to hack your account. Don't worry, Nothing will happen to your account if your password isn't weak. To Change Password click <a href='" . HOST . "/me/ChangePassword'>here</a>.");
   $OP->sss("Confirmation Link Sent", "A Confirmation Link has been sent to your E-Mail address. Follow the instructions in the E-Mail.<br/> You can close this window if you want.");
  }
  ?>
 </div>
</body></html>
