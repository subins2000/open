<?include("../comps/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?include("../comps/head.php");?>
</head><body>
 <?include("../comps/header.php");?>
 <div class="content">
  <h1>Manage Account</h1>
  <p>Here, in this page you can manage your account such as <b>Password Changing</b></p><cl/>
  <center style="border-bottom:1px solid black;">
   <a href="ChangePassword"><button>Change Password</button><cl/></a>
   <a href="ChangeTheme"><button class="b-green">Change Theme</button><cl/></a>
   <a href="Connections"><button class="b-red">Manage Connections</button><cl/></a>
   <a href="Linked"><button class="b-white">Manage Linked Accounts</button><cl/></a>
  </center>
  <cl/>
  <p>Did you ever Found A bug / Had a suggestion ? Please report/suggest at <b>Bug Reporter Page</b>. The feedback you give, helps us to make this site more powerful, great and awesome.<br/> Are you a <b>Web Developer</b> / a <b>Web Designer</b> ? If yes, you can join our <b>Open Source Community</b>. We would love to have developers like you.</p>
  <?
  include("../comps/project_urls.php");
  ?>
 </div>
</body></html>
