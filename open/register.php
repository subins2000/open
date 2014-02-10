<?include("comps/config.php");if($lg){header("Location:home.php");}?>
<!DOCTYPE html>
<html><head>
 <?$t="Sign Up";$fs="pc";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content" style="width:320px;">
  <button style="padding:5px 25px;" onclick="$('#social').show();$('#classic').hide();">Social Sign Up</button><button style="padding:5px 25px;" onclick="$('#social').hide();$('#classic').show();">Form Sign Up</button>
  <div id="social" style="<?if(isset($_POST['submit']) || isset($_POST['verify'])){?>display:none;<?}?>">
   <h1>Social Sign Up</h1>
   <a href="http://open.subinsb.com/oauth/login_with_facebook"><img src="http://open.subinsb.com/img/fb_login.png"/></a><cl/>
   <a href="http://open.subinsb.com/oauth/login_with_google"><img src="http://open.subinsb.com/img/google_login.png"/></a>
  </div>
  <div style="<?if(!isset($_POST['submit']) && !isset($_POST['verify'])){?>display:none;<?}?>" id="classic">
   <h1>Sign Up</h1>
   <?
   $cde=$_POST['code'];
   if(isset($_POST['verify']) && $cde!=""){
    $dcde=decrypter($cde);
    $vfr=$_POST['user']."oauth_985_login";
    if($vfr==$dcde){
     $verified=1;
    }else{
     $verified=0;
    }
   }
   if($verified==1){
   ?>
   <form action="register" method="POST" style="padding-left:15px;padding-top:1px;">
    <input name="user" value="<?echo$cde;?>" type="hidden"/>
    <input name="nuser" value="<?echo$_POST['user'];?>" type="hidden"/>
    <h2>E-Mail</h2>
    <input type="text" style="width:290px;" value="<?echo$_POST['user'];?>" disabled="disabled"/><br/>
    <h2>Password</h2>
    <input name="pass" style="width:290px;" id="pass" placeholder="Make It Great" autocomplete="off" type="password"/><br/>
    <h2>Retype Password</h2>
    <input name="pass2" style="width:290px;" id="pass2" placeholder="Is It that great ?" autocomplete="off" type="password"/><br/>
    <div id="ppbar" title="Strength"><div id="pbar"></div></div>
    <div id="ppbartxt"></div>
    <h2>Name</h2>
    <input name="name" style="width:290px;" id="user" placeholder="You Must Have a Name" type="text"/><br/><cl/>
    <input name="submit" type="submit" value="Sign Up"/><cl/>
    Already Have An Account ?
    <a href="http://open.subinsb.com/login"><input type="button" value="Sign In"/></a>
   </form>
   <?
   }elseif(!isset($_POST['verify']) && !isset($_POST['submit'])){
   ?>
   <form action="register" method="POST">
    Type In Your E-Mail To Continue Signup Process.<br/><cl/>
    <input name="mail" style="width:290px;" placeholder="Don't You Have An E-Mail ?" type="text"/><br/><cl/>
    <input name="verify" type="submit" value="Verify E-Mail"/><cl/>
    You can only sign up if you verify your email.
   </form>
   <?
   }elseif($verified==0 && isset($_POST['verify']) && $cde!=""){
    ser("Wrong Verification Code","The Code you entered is wrong.");
   }
   if(isset($_POST['verify']) && !isset($_POST['code'])){
    $u=$_POST['mail'];
    if(!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/',$u)){
     ser("E-Mail Is Not Valid","The E-Mail you submitted is not a valid E-Mail");
    }
    $sql=$db->prepare("SELECT * FROM users WHERE username=?");
    $sql->execute(array($u));
    if($sql->rowCount()!=0){
     ser("You Already Have An Account!","There is already an account registered with the E-Mail you have given. <a href='http://open.subinsb.com/me/ResetPassword'>Forgot Password ?</a>");
    }
    send_mail($u,"Verify Your E-Mail","You requested for registering on Open. For signing up, you need to verify your E-Mail address. Paste the code below in the input field of the page where you requested for signing up.<blockquote>".encrypter($_POST['mail']."oauth_985_login")."</blockquote>");
   ?>
   An E-Mail containing a code have been sent to the E-Mail address you gave us. Check Your Inbox for that mail. The mail might have went to the SPAM folder. Hence you have to check that folder too.<cl/>
   <form action="register" method="POST">
    Paste The Code you received via E-Mail below<br/><cl/>
    <input name="user" value="<?echo$u;?>" type="hidden"/>
    <input name="code" style="width:290px;" autocomplete="off" placeholder="Paste The Code Here" type="text"/><br/><cl/>
    <input name="verify" type="submit" value="Complete Verification"/><cl/>
   </form>
   <?
   }
   if(isset($_POST['submit'])){
    $u=strtolower(filt($_POST['nuser']));
    $p=filt($_POST['pass']);
    $p2=filt($_POST['pass2']);
    $n=str_replace("@","",
     str_replace("*","",
      str_replace("(","",
       str_replace(")","",
        filt($_POST['name'])
       )
      )
     )
    );
    if($u=="" || $p=='' || $p2=='' || $n==''){
     ser("Fields Left Blank","Some Fields were left blank. Please fill up all fields. You now have to start over the signup process.");
    }
    if($p!=$p2){
     ser("Passwords Don't Match","The Passwords you entered didn't match");
    }
    $dcde=decrypter($_POST['user']);
    $vfr=$_POST['nuser']."oauth_985_login";
    if($vfr!=$dcde){
     ser("User Not Verified.","The user in which this form was sent have not verified his/her E-Mail.");
    }
    function ras($length){$str="";$chars='q!f@g#h#n$m%b^v&h*j(k)q_-=jn+sw47894swwfv1h36y8re879d5d2sd2sdf55sf4rwejeq093q732u4j4320238o/.Qkqu93q324nerwf78ew9q823';$size=strlen($chars);for($i=0;$i<$length;$i++){$str.=$chars[rand(0,$size-1)];}return$str;}
    $r_salt=ras(25);
    $site_salt=")%*@*%!&%^)#@-_+`=~";
    $salted_hash=hash('sha256',$p.$site_salt.$r_salt);
    $json='{"joined":"'.date("Y-m-d H:i:s").'"}';
    $sql=$db->prepare("INSERT INTO users (username,password,psalt,name,udata) VALUES(?,?,?,?,?)");
    $sql->execute(array($u,$salted_hash,$r_salt,$n,$json));
    sss("Registration Success","Your account has been created. Sign In <a href='login'>here</a>");
    header('Location:home.php');
   }
   ?>
  </div>
  <p style="margin-top:10px;border-top:1px solid black;">
   By using Open after signing up, you are agreeing to our <a href="open.pdf">Terms & Conditions</a>.
  </p>
 </div>
 <style>#ppbar{background:#CCC;width:300px;height:15px;margin:5px;}#pbar{margin:0px;width:0px;background:lightgreen;height: 100%;}#ppbartxt{text-align:right;margin:2px;}</style>
</body></html>
