<?include("config.php");ch();?>
<!DOCTYPE html>
<html><head>
<style>*{font-family:ubuntu;}</style>
</head><body>
 <h1>Upload A Profile Picture</h1>
 <form method="POST" enctype="multipart/form-data" action="http://open.subinsb.com/comps/profile_pic">
  <input type="file" name="file"/>
  <input type="submit" value="Upload Picture"/>
 </form>
 <?
 $f=$_FILES['file'];
 if(isset($f)){
  include("../data/add.php");
  $up=upload($who,"img",$f);
  if($up){
   save("img",$up);
   sss("Uploaded Image","The Image you gave was successfully uploaded & it has been made as your profile picture. <b>Reload Page to see changes.</b>");
  }else{
   ser("Error Uploading Image","Something Happened. Try Uploading again.");
  }
 }else{
  echo"<br/><span>Choose A Picture and click Upload Picture</span>";
 }
 ?>
</body></html>
