<?
$_GET['c']=isset($_GET['c']) ? $_GET['c']:"";
$c=$OP->format($_GET['c']);
$c=$c=="" ? HOST . "/home":$c;
if(isset($_POST['submit'])){
 $u=$OP->format($_POST['user']);
 $pa=$OP->format($_POST['pass']);
 $sql=$OP->dbh->prepare("SELECT * FROM users WHERE username=?");
 $sql->execute(array($u));
 while($r=$sql->fetch()){
  $p=$r['password'];
  $p_salt=$r['psalt'];
  $id=$r['id'];
 }
 $site_salt="private_value";
 $salted_hash = hash('sha256', $pa.$site_salt.$p_salt);
 if($p==$salted_hash){
  if(isset($_POST['remember_me'])){
   $tme=time()+4950000;
  }else{
   $tme=time()*60+1200;
  }
  setcookie("curuser", $id, $tme, "/", CLEAN_HOST);
  setcookie("wervsi", $OP->encrypter($id), $tme, "/", CLEAN_HOST);
  header("Location:$c");
 }else{
  $er='E-Mail/Password is Incorrect';
 }
}
if(isset($_GET['logout']) && $_GET['logout']=="true"){
 $tme=time()-301014600;
 setcookie("curuser", "", $tme, "/", $_SERVER['HTTP_HOST']);
 setcookie("wervsi", "", $tme, "/", $_SERVER['HTTP_HOST']);
 header("Location:" . HOST);
}elseif($OP->lg){$OP->$OP->redirect("$c");}
?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("Sign In");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content blocks" style="text-align:center;">
  <div class="block" style="width:200px;padding:0px 20px 20px;text-align:left;">
   <h1>Sign In</h1>
   <form action="login?c=<?echo$c;?>" method="POST">
    <div style="margin:5px 0px;">
     E-Mail<br/>
     <input name="user" type="text" valuesize="20"/>
    </div>
    <div>
     Password<br/>
    <input name="pass" autocomplete="off" type="password" size="20"/>
    </div><cl/>
    <label class="blocks">
     <input type="checkbox" class="block" name="remember_me"/><span class="block">Remember Me</span>
    </label><cl/>
    <div>
     <input name="submit" type="submit" value="Sign In"/>
     <a href="<?echo HOST;?>/register" class="button b-green">Sign Up</a>
    </div><cl/>
    <a href="<?echo HOST;?>/me/ResetPassword" class="button b-red">Forgot Password ?</a>
   </form>
   <?
   if(isset($er)){
    echo "<div style='color:red;margin: 5px -5px;'>$er</div>";
   }
   ?>
  </div>
  <div class="block" style="width:200px;text-align:left;padding:0px 20px 20px;">
   <h1>Social Sign In</h1>
   <a href="<?echo HOST;?>/oauth/login_with_facebook?c=<?echo$c;?>"><img src="<?echo HOST;?>/source/cdn/img/fb_login.png"/></a><cl/>
   <a href="<?echo HOST;?>/oauth/login_with_google?c=<?echo$c;?>"><img src="<?echo HOST;?>/source/cdn/img/google_login.png"/></a>
  </div>
 </div>
</body></html>
