<?
include('../comps/config.php');
ch();
$themes=array("dark"=>array("-webkit-linear-gradient(#AAA,#EEE)","-moz-linear-gradient(left,#AAA,#EEE)"),"cloud"=>array("url(//open.subinsb.com/img/clouds) rgba(100, 172, 400,.8)","url(//open.subinsb.com/img/clouds) rgba(100, 172, 400,.8)","10em"),"normal"=>array("url(//open.subinsb.com/img/dot.gif)","url(//open.subinsb.com/img/dot.gif)"),"india"=>array("-webkit-linear-gradient(rgba(241, 158, 32, 0.7),white,rgba(65, 199, 53, .8))","-moz-linear-gradient(rgba(241, 158, 32, 0.7),white,rgba(65, 199, 53, .8))"));
if(isset($_POST['theme'])){
 $theme=strtolower($_POST['theme']);
 if($theme=="dark" || $theme=="cloud" || $theme=='normal' || $theme=='india'){
  if($theme=='normal'){$theme="";}
  save("theme",$theme);
  $ss=array("Success","Your theme has been changed. Reload Page to see changes.");
 }else{
  $er=array("Error","No theme was found with that name");
 }
}
?>
<!DOCTYPE html><html><head>
<?$t="Change Theme - Manage Account";include("../comps/head.php");?>
</head><body>
 <?include("../comps/header.php");?>
  <div class="content">
  <h2>Change Theme</h2>
  <div style="margin:0px auto;width:67%;">
   <form action="ChangeTheme" method="POST">
    <?
    foreach($themes as $k=>$v){?>
     <div style="background:<?echo$v[0];?>;background:<?echo$v[1];?>;height:150px;width:150px;display:inline-block;position:relative;background-size:<?echo$v[2];?>;">
      <div style='position:absolute;left:0px;right:0px;padding:5px;bottom:0px;background:black;color:white;'><input name="theme" type="submit" value="<?echo strtoupper($k);?>"/></div>
     </div>
    <?}?>
    <span style="color:red;">
     <?
     if(count($er)==2){
      ser($er[0],$er[1]);
     }elseif(count($ss)==2){
      sss($ss[0],$ss[1]);
     }
     ?>
    </span>
   </form>
  </div>
 </div>
</body></html>
