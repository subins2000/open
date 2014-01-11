<?include("comps/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?$t="Home";$cfs="home,post_form,ac";$fs="ac,post_form,home,time";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content">
  <div class="left viewpoint">
   <?
   include("comps/trending.php");
   include("comps/suggest.php");
   ?>
  </div>
  <div class="right viewpoint">
   <?
   include("comps/post_form.php");
   ?>
   <div class="feed">
    <?
    include("comps/feed.php");
    ?>
   </div>
  </div>
 </div>
</body></html>
