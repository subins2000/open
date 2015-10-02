<?php
\Fr\LS::init();
if( !isset($_GET['id']) || $_GET['id'] == "" ){
  $OP->ser();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="type" value="view"></meta>
    <?php $OP->head("View Post", "ac,time,post_form,home", "ac,home,post_form");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content" style="width:510px;">
        <div class="feed">
          <?php
          $_POST['all'] = 1;
          include "$docRoot/inc/feed.php";
          ?>
          <script>$(".comments .cmt_form").find("#clod").val("mom");$(".comments").show();</script>
        </div>
      </div>
    </div>
  </body>
</html>