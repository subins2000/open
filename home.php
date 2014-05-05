<?include("inc/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?$t="Home";$cfs="home,post_form,ac,gadget";$fs="ac,post_form,home,time,gadget";include("inc/head.php");?>
</head><body>
 <?include("inc/header.php");?>
 <div class="content blocks">
  <div class="viewpoint left block">
   <?
   include("inc/trending.php");
   include("inc/suggest.php");
   ?>
  </div>
  <div class="viewpoint right block">
   <?
   include("inc/post_form.php");
   ?>
   <div class="feed">
    <?
    include("inc/feed.php");
    ?>
   </div>
  </div>
 </div>
 <?include("inc/gadget.php");?>
</body></html>
