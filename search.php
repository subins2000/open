<?include("inc/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?$t="Search";$cfs="ac,home,post_form,gadget";$fs="ac,post_form,home,time,gadget";include("inc/head.php");?>
</head><body>
 <?include("inc/header.php");?>
 <div class="content">
  <div class="left viewpoint">
   <?
   include("inc/trending.php");
   ?>
  </div>
  <div class="right viewpoint">
   <form action="search" method="GET">
    <input type="text" autocomplete="off" name="q" size="40" value="<?echo$q;?>"/>
    <input type="submit" value="Search"/>
   </form><br/>
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
