<?php $LS->init();?>
<!DOCTYPE html>
<html><head>
 <?php
 $imgs=array(
  0 => "00.png",
  1 => "01.jpg",
  2 => "02.jpg",
  3 => "03.jpg",
  4 => "04.jpg"
 );
 ?>
</head><body>
 <?php
 if(isset($_GET['id'])){
  $id=$_GET['id'];
  if($imgs[$id]!=""){
   $OP->save("header", HOST . "/cdn/img/headers/".$imgs[$id]);
   $OP->sss("Saved", "Your new header image was saved successfully. See your profile to see changes.");
  }else{
   $OP->ser("Error", "There was an error saving your header image. Please Try again.");
  }
 }else{
  echo"<h2>Change Header Image</h2>";
 }
 ?>
 <center style="margin-top:10px;">
  <?php
  foreach($imgs as $k=>$v){
   echo "<a href='?id=$k'><img src='" . HOST . "/cdn/img/headers/$v' width='600' height='180' /></a>";
  }
  ?>
 </center>
</body></html>
