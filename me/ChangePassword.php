<?
include('../comps/config.php');
ch();
if(isset($_POST['submit'])){
 if($_POST['old']=="OAuth_Login_Password"){
  $_POST['old']="a_password";
 }
 if($_POST['old']!='' && $_POST['new']!='' && $_POST['new2']!=''){
  if($_POST['new']!=$_POST['new2']){
   $er=array("Passwords don't match");
  }
  $sql=$db->prepare("SELECT * FROM users WHERE id=?");
  $sql->execute(array($who));
  while($r=$sql->fetch()){
   $usalt=$r['psalt'];
   $up=$r['password'];
  }
  $site_salt="a_salt_key";
  $salted_hash = hash('sha256',$_POST['old'].$site_salt.$usalt);
  if($up!=$salted_hash){
   $er=array("Error","Password you entered is wrong.");
  }
  if(preg_match('/.{6,100}/',$_POST['new'])==false){
   $er=array("Error","Password must contain atleast 6 characters.");
  }
  $sql=$db->prepare("UPDATE users SET password=?,psalt=? WHERE id=?");
  function ras($length){$chars='q!f@g#h#n$m%b^v&h*j(k)q_-=jn+sw47894swwfv1h36y8re879d5d2sd2sdf55sf4rwejeq093q732u4j4320238o/.Qkqu93q324nerwf78ew9q823';$size=strlen($chars);for($i=0;$i<$length;$i++){$str.=$chars[rand(0,$size-1)];}return$str;}
  $rsalt=ras('25');
  $salted_hash = hash('sha256',$_POST['new'].$site_salt.$rsalt);
  $sql->execute(array($salted_hash,$rsalt,$who));
  $tme=time()-301014600;
  setcookie("curuser", "", $tme, "/", $_SERVER['HTTP_HOST']);
  setcookie("wervsi", "", $tme, "/", $_SERVER['HTTP_HOST']);
  $ss=array("Password Changed","Your Password was successfully changed.<br/><a href='//open.subinsb.com/login'>Log In with your new password.</a>");
 }else{
  $er=array("Fields Left Blank!","Please fill up all the fields.");
 }
}
$upa=get("password",$who,false);
$p_salt=get("psalt",$who,false);
$site_salt="a_salt_key";
$salted_hash = hash('sha256',"a_password".$site_salt.$p_salt);
echo$salted_hash;
if($upa==$salted_hash){
 $Opass="OAuth_Login_Password";
}
?>
<!DOCTYPE html><html><head>
<?$t="Change Password - Manage Account";include("../comps/head.php");?>
</head><body>
 <?include("../comps/header.php");?>
  <div class="content">
  <h2>Change Password</h2>
  <div style="margin:0px auto;width: 60%;">
   <form action="ChangePassword" method="POST">
    <?if($Opass!=''){?>
     <input name="old" value="<?echo$Opass?>" type="hidden"/>
    <?}?>
    <table>
     <tbody>
      <tr><td>Current Password:</td><td><input autocomplete="off" type="password" value="<?if($Opass!=''){echo$Opass;}?>"<?if($Opass!=''){echo"disabled='disabled'";}?> placeholder="Type Password you use to login to Open" size="32" name="old"/></td></tr>
      <tr><td>New Password:</td><td><input placeholder="Type a new Password" autocomplete="off" type="password" name="new" size="32"/></td></tr>
      <tr><td>Retype Password:</td><td><input placeholder="Retype the new Password" autocomplete="off" type="password" name="new2" size="32"/></td></tr>
      <tr><td></td><td><input type="submit" name="submit"/></td></tr>
     </tbody>
    </table>
    <span style="color:red;">
     <?
     if(count($er)==2){
      ser($er[0],$er[1]);
     }elseif(count($ss)==2){
      sss($ss[0],$ss[1]);
     }
     ?>
    </span>
   </form>
  </div>
 </div>
</body></html>
