<?include("comps/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?$t="Search";$cfs="ac,home,post_form";$fs="ac,post_form,home,time";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content">
  <div class="left viewpoint">
   <?
   include("comps/trending.php");
   ?>
  </div>
  <div class="right viewpoint">
   <form action="search" method="GET">
    <input type="text" autocomplete="off" name="q" size="40" value="<?echo$q;?>"/>
    <input type="submit" value="Search"/>
   </form><br/>
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
