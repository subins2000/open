<?
include("inc/config.php");
ch();
if(!isset($_GET['id']) || $_GET['id']==""){
 ser();
}
if($_SERVER['SCRIPT_NAME']=="/view.php"){/* We don't want view?id= URLs anymore */
 $To=$_GET['id']=="" ? "":"/{$_GET['id']}";
 redirect("/view$To", 301); /* 3rd Param is the status code and not the 2nd */
}
?>
<!DOCTYPE html>
<html><head>
 <meta name="type" value="view"></meta>
 <?$t="View Post";$cfs="ac,home,post_form";$fs="ac,time,post_form,home";include("inc/head.php");?>
</head><body>
 <?include("inc/header.php");?>
 <div class="content" style="width:510px;">
  <div class="feed">
   <?
    $_POST['all']=1;
    include("inc/feed.php");
   ?>
   <script>$(".comments .cmt_form").find("#clod").val("mom");$(".comments").show();</script>
  </div>
 </div>
</body></html>
