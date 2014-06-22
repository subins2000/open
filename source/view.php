<?

$OP->init();
if(!isset($_GET['id']) || $_GET['id']==""){
 $OP->ser();
}
if($_SERVER['SCRIPT_NAME']=="/view.php"){/* We don't want view?id= URLs anymore */
 $To=$_GET['id']=="" ? "":"/{$_GET['id']}";
 $OP->$OP->redirect("/view$To", 301); /* 3rd Param is the status code and not the 2nd */
}
?>
<!DOCTYPE html>
<html><head>
 <meta name="type" value="view"></meta>
 <?$OP->head("View Post", "ac,time,post_form,home", "ac,home,post_form");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content" style="width:510px;">
  <div class="feed">
   <?
    $_POST['all']=1;
    $OP->inc("inc/feed.php");
   ?>
   <script>$(".comments .cmt_form").find("#clod").val("mom");$(".comments").show();</script>
  </div>
 </div>
</body></html>
