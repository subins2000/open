<?include("comps/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?$t="View Post";$cfs="ac,home,post_form";$fs="ac,post_form,home,time";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content" style="width:510px;">
  <div class="feed">
   <?
   $_POST['all']=1;
   include("comps/feed.php");
   ?>
   <script>$(".comments .cmt_form").find("#clod").val("mom");$(".comments").show();</script>
  </div>
 </div>
</body></html>
