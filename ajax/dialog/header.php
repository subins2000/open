<?include("../../comps/config.php");ch();?>
<!DOCTYPE html>
<html><head>
 <?
 $imgs=array(
  0 => "00.png",
  1 => "01.jpg",
  2 => "02.jpg",
  3 => "03.jpg",
  4 => "04.jpg"
 );
 ?>
</head><body>
 <?
 if(isset($_GET['id'])){
  $id=$_GET['id'];
  if($imgs[$id]!=""){
   save("header","http://open.subinsb.com/img/headers/".$imgs[$id]);
   sss("Saved","Your new header image was saved successfully. See your profile to see changes.");
  }else{
   ser("Error","There was an error saving your header image. Please Try again.");
  }
 }else{
  echo"<h2>Change Header Image</h2>";
 }
 ?>
 <center style="margin-top:10px;">
  <?
  foreach($imgs as $k=>$v){
   echo "<a href='?id=$k'><img src='http://open.subinsb.com/img/headers/$v' width='600' height='180' /></a>";
  }
  ?>
 </center>
</body></html>
