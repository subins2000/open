<?$OP->init();?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("Home", "ac,post_form,home,time,gadget", "home,post_form,ac,gadget");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content blocks">
  <div class="viewpoint left block">
   <?
   include "$docRoot/inc/post_form.php";
   ?>
   <div class="feed">
    <?
    include "$docRoot/inc/feed.php";
    ?>
   </div>
  </div>
  <div class="viewpoint right block">
   <?
   include "$docRoot/inc/trending.php";
   include "$docRoot/inc/suggest.php";
   ?>
  </div>
 </div>
 <?include "$docRoot/inc/gadget.php";?>
</body></html>