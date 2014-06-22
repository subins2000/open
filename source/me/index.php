<?$OP->init();?>
<!DOCTYPE html>
<html><head>
 <?$OP->inc("inc/head.php");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content">
  <h1>Manage Account</h1>
  <p>Here, in this page you can manage your account such as <b>Password Changing</b></p><cl/>
  <center style="margin:0px auto;display:table;">
   <div><a href="ChangePassword" class="button">Change Password</a></div><cl/>
   <div><a href="Notify" class="button b-green">Notifications</a></div><cl/>
   <div><a href="Connections" class="button b-red">Manage Connections</a></div><cl/>
   <div><a href="Linked" class="button b-white">Manage Linked Accounts</a></div><cl/>
  </center>
  <style>.content center .button{width: 100%;}</style>
  <cl/>
  <p>Did you ever Found A bug / Had a suggestion ? Please report/suggest at <b>Bug Reporter Page</b>. The feedback you give, helps us to make this site more powerful, great and awesome.<br/> Are you a <b>Web Developer</b> / a <b>Web Designer</b> ? If yes, you can join our <b>Open Source Community</b>. We would love to have developers like you.</p>
  <?
  $OP->inc("inc/project_urls.php");
  ?>
 </div>
</body></html>
