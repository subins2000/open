<?include("inc/config.php");ch();?>
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
