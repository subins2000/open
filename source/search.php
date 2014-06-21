<?
$OP->init();
?>
<!DOCTYPE html>
<html><head>
 <meta name="type" value="search"></meta>
 <?$OP->head("Search", "ac,post_form,home,time,gadget", "ac,home,post_form,gadget");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content blocks">
  <div class="block left viewpoint">
   <form action="search" method="GET">
    <input type="text" class="block" name="q" id="q" />
    <button class="block">Search</button>
   </form>
   <style>
    #q{
     width:375px;
     margin: 0px;
    }
    @media screen and (max-width:500px){
     #q{
      width:auto;
     }
    }
   </style>
   <?
   include "$docRoot/inc/post_form.php";
   ?>
   <div class="feed">
    <?
    include "$docRoot/inc/feed.php";
    ?>
   </div>
  </div>
  <div class="block right viewpoint">
   <?
   include_once "$docRoot/inc/trending.php";
   echo '<script>document.getElementById("q").value="'.$searchQuery.'";</script>';
   ?>
  </div>
 </div>
 <?$OP->inc("inc/gadget.php");?>
</body></html>