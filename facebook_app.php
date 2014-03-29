<!DOCTYPE html>
<html><head>
 <link rel="stylesheet" href="https://open-phpgeek.rhcloud.com/css/main.css"/>
</head><body>
 <script>window.top.location.href = "http://open.subinsb.com";</script>
 <?include("inc/header.php");?>
 <div class="content">
  <h1>Welcome</h1>
  Welcome To <a href="http://open.subinsb.com" target="_blank">Open</a>  - The Open Source Social Network.
  <h3>Please go to <a href="http://open.subinsb.com" target="_blank">http://open.subinsb.com</a> for full width better usage of <a href="http://open.subinsb.com" target="_blank">Open</a>.</h3>
  This page is just for the Open Facebook App. You should go to The Main <a href="http://open.subinsb.com" target="_blank">Open</a> site for using our service.
 </div>
 <script>
 $a=document.getElementsByTagName("a");
 for(i=0;i<$a.length;i++){
  $el=$a[i];
  if($el.getAttribute("target")!="_blank"){
   $el.setAttribute("target","_blank");
  }
 }
 </script>
</body></html>
