<?php \Fr\LS::init();?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Home", "ac,post_form,home,time,gadget", "home,post_form,ac,gadget");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content blocks">
        <div class="viewpoint left block">
          <?php
          include "$docRoot/inc/post_form.php";
          ?>
          <div class="feed">
            <?php
            include "$docRoot/inc/feed.php";
            ?>
          </div>
        </div>
        <div class="viewpoint right block">
          <?php
          include "$docRoot/inc/trending.php";
          include "$docRoot/inc/suggest.php";
          ?>
        </div>
      </div>
    </div>
    <?php include "$docRoot/inc/gadget.php";?>
  </body>
</html>