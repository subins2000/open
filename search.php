<?
include("inc/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <meta name="type" value="search"></meta>
 <?$t="Search";$cfs="ac,home,post_form,gadget";$fs="ac,post_form,home,time,gadget";include("inc/head.php");?>
</head><body>
 <?include("inc/header.php");?>
 <div class="content blocks">
  <div class="block left viewpoint">
   <?
   include("inc/trending.php");
   ?>
  </div>
  <div class="block right viewpoint">
   <form action="http://open.subinsb.com/search" method="GET">
    <input type="text" class="block" name="q" id="q" value="<?echo$q;?>"/>
    <button class="block">Search</button>
   </form>
   <style>
    #q{
     width:375px;
    }
    @media screen and (max-width:500px){
     #q{
      width:auto;
     }
    }
    </style>
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