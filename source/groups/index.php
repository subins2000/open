<?php include("../load.php");?>
<!DOCTYPE html>
<html><head>
 <?php include("../inc/head.php");?>
</head><body>
 <?php include("../inc/header.php");?>
 <div class="wrapper"><div class="content">
  <h2>Groups</h2>
  <p>Hang around with your friends. Make groups and group chat.</p>
  <?php
  $inGroups=$OP->getGroups();
  if(count($inGroups)==0){
   $OP->ser("<center>No Groups</center>", "You haven't joined any groups or created any groups. <a href='". HOST ."/groups/new'>Create Group</a>");
  }
  ?>
 </div></div>
</body></html>