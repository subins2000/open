<?
include('../comps/config.php');
$id=urldecode($_GET['id']);
$sql=$db->prepare("SELECT * FROM verify WHERE code=?");
$sql->execute(array($id));
if($sql->rowCount()==0){
 ser();
}
while($r=$sql->fetch()){
 $fwho=$r['uid'];
}
?>
<!DOCTYPE html><html><head>
<?$t="Confirm Password Reset - Manage Account";include("../comps/head.php");?>
</head><body>
 <?include("../comps/header.php");?>
 <div class="content">
  <h2>Reset Password</h2>
  <div style="margin:0px auto;width:600px;">
   <form action="ConfirmPasswordReset?id=<?echo urlencode($id);?>" method="POST">
    <table>
     <tbody>
      <tr><td>New Password:</td><td><input placeholder="Type new Password" autocomplete="off" type="password" name="new"/></td></tr>
      <tr><td>Retype Password:</td><td><input placeholder="Retype new Password" autocomplete="off" type="password" name="new2"/></td></tr>
      <tr><td></td><td><input type="submit"/></td></tr>
     </tbody>
    </table>
    <span style="color:red;">
     <?
     if($_POST['new']!='' && $_POST['new2']!=''){
      if($_POST['new']!=$_POST['new2']){
       ser("Error","Passwords don't match");
      }
      if(preg_match('/.{6,100}/',$_POST['new'])==false){
       ser("Error","Password must contain atleast 6 characters.");
      }
      function ras($length){$chars='q!f@g#h#n$m%b^v&h*j(k)q_-=jn+sw47894swwfv1h36y8re879d5d2sd2sdf55sf4rwejeq093q732u4j4320238o/.Qkqu93q324nerwf78ew9q823';$size=strlen($chars);for($i=0;$i<$length;$i++){$str.=$chars[rand(0,$size-1)];}return$str;}
      $rsalt=ras('25');
      $site_salt=")%*@*%!&%^)#@-_+`=~";
      $salted_hash = hash('sha256',$_POST['new'].$site_salt.$rsalt);
      $sql=$db->prepare("UPDATE users SET password=?,psalt=? WHERE id=?;DELETE FROM verify WHERE code=?");
      $sql->execute(array($salted_hash,$rsalt,$fwho,$id));
      sss("Password Successfully changed","Your Password has been changed. Sign In Wih your new password.");
     }
     ?>
    </span>
   </form>
  </div>
 </div>
</body></html>
