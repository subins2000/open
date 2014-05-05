<?
include("inc/config.php");
$ceb=isset($_GET['request']) ? $_GET['request'] : "";
$uP=explode("/", $ceb);
if(count($uP)>1){
 list($uP1, $uP2)=$uP;
 $uP2=substr($uP2, -4, 4)==".php" ? substr_replace($uP2, "", -4, 4):$uP2;
}else{
 list($uP1)=$uP;
}
if($ceb!="" && !file_exists("$ceb.php") && !file_exists("$ceb")){
 if(preg_match("/[0-9]/", $uP1) || $uP1=="profile"){
  $_GET['id']=$uP1=="profile" ? "":$uP1;
  if(isset($uP2) && ($uP2=="about" || $uP2=="reputation" || $uP2=="feed")){
   $_GET['part']=$uP2;
   include("profile.php");
  }elseif(!isset($uP2)){
   include("profile.php");
  }else{
   ser();
  }
 }elseif($uP1=="view"){
  if(isset($uP2)){
   $_GET['id']=$uP2;
  }
  $_SERVER['PHP_SELF']="/view.php";/* A Fake One (2 May) */
  include("view.php");
 }elseif($uP1=="chat"){
  if(isset($uP2)){
   $_GET['id']=$uP2;
  }
  $_SERVER['PHP_SELF']="/chat.php";/* A Fake One (2 May) */
  include("chat.php");
 }elseif($uP1=="find"){
  if(isset($uP2)){
   $_GET['q']=$uP2;
  }
  $_SERVER['PHP_SELF']="/find.php";/* Only PHP_SELF is fake. Others are the original */
  include("find.php");
 }else{
  ser();
 }
}elseif(!file_exists("$ceb") && $ceb!=""){
 ser();
}else{
 ch();
?>
<!DOCTYPE html>
<html><head>
 <?include("inc/head.php");?>
</head><body>
 <?include("inc/header.php");?>
 <div style="width:100%;background:rgba(100, 194, 53,.7);margin-top:47px;color:white;">
  <div class="icontent">
   <div class="left">
    <img src="cdn/img/logo.png"/>
   </div>
   <div class="right">
    <h2>Let the Doors Open</h2>
    <p>
     Open is a Social Network like Twitter and Facebook.<br/>&nbsp;&nbsp;&nbsp;The only difference is that Open is an Open Source <br/>Social Network unlike others.
    </p>
   </div>
  </div>
 </div>
 <div style="width:100%;background:white;color:white;text-align:center;">
  <div class="icontent">
   <a href="oauth/login_with_facebook" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(//open.subinsb.com/cdn/img/fb_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
   <a href="oauth/login_with_google" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(//open.subinsb.com/cdn/img/g+_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
   <div style="font-size: 30px;margin-top: 20px;height: 25px;">
    <?
    $sql=$db->prepare("SELECT COUNT(seen) FROM users");
    $sql->execute();
    $count=$sql->fetchColumn();
    foreach(str_split($count) as $v){
     echo "<span style='background:black;padding: 2px 10px;border-right: 1px solid white;'>$v</span>";
    }
    ?>
   </div>
   <div style="background: rgb(100, 172, 255);width: 100px;margin: 10px auto 0px;padding: 5px 30px;">Users So Far</div>
  </div>
 </div>
 <div style="width:100%;background:rgba(241, 158, 32, 0.7);color:white;">
  <div class="icontent">
   <div class="left">
    <h2>What's Open Source ?</h2>
    <p>
     If you're a newbie to the Computer World,<br/> you will have this question. Open Source <br/>Softwares are softwares that make<br/>their source code <font size="5">Public</font>.<br/>&nbsp;&nbsp;&nbsp;Anyone can edit the source code and use it <br/>for their purposes. The main AIM of Open Source<br/> is to develop something with the help of all the<br/>people in the world. Anyone who contributed <br/>something to an Open Source project is a part of<br/>that project.
    </p>
   </div>
   <div class="right">
    <img src="cdn/img/ex_code2.png" width="160"/>
   </div>
  </div>
 </div>
 <div style="width:100%;background:white;">
  <div class="icontent">
   <div class="left">
    <img src="cdn/img/ex_post.png" width="120"/>
   </div>
   <div class="right">
    <h2>What's the Benefit ?</h2>
    <p>
     Privacy problems are the most discussed<br/> issue on this planet. Every where we go on web,<br/>someone are tracking us. 21st Century<br/>Social Networks are spies.<br/>&nbsp;&nbsp;&nbsp;They know the every moves we make on the web <br/>and they use that moves to make money.<br/>But at Open, you are the owner. You have all the<br/> freedom. You can check out the source code, <br/>to see if we are tracking any moves of yours.
    </p>
   </div> 
  </div>
 </div>
 <div style="width:100%;background:rgba(65, 199, 53, .8);color:white;">
  <div class="icontent">
   <div class="left">
    <img src="cdn/img/ex_code.png" width="165"/>
   </div>
   <div class="right">
    <h2>Features</h2>
    <p>
     <ol>
      <li>No ADS</li>
      <li>Safe Browsing</li>
      <li>Post To Facebook</li>
      <li>Post To Twitter</li>
      <li>No Vulnerabilities Found</li>
      <li>Privacy Settings</li>
     </ol>
    </p>
   </div>
  </div>
 </div>
 <div style="width:100%;background:#64ACFF;color:white;">
  <div class="icontent">
   <div class="left">
    <h2>How Can I contribute ?</h2>
    <p>
     Open is a developing social network. It will take years <br/>for it to be perfect. If you find any flaws/errors or have <br/>a suggestion, please report it on our Project Page.<br/><cl/><?include("inc/project_urls.php");?>
     <br/>You can Find Documentation, news, info <br/>about Open on our blog.<br/><cl/>
     <a href="blog"><button class="b-white">Blog</button></a>
    </p>
   </div>
   <div class="right">
    <img src="cdn/img/ex_code3.png" height="179"/>
   </div>
  </div>
 </div>
 <div style="background:black;padding:10px 15px;margin-top:30px;box-shadow: 0 0 28px rgb(170, 170, 170);color:white;">
  &copy; 2013-<?echo date("Y")+1;?> Open <div style="float:right;">An initiative by <a href="1">Subin Siby</a>. Licensed Under General Public License (GPL).</div>
 </div>
 <style>
  .icontent{
   width:600px;
   display:table;
   margin:0px auto;
   padding:15px;
  }
  .icontent .right,.icontent .left{
   display:inline-block;
   vertical-align:top;
   margin-left:10px;
   line-height:20px;
  }
  .icontent .right{
   padding-left:10px;
  }
 </style>
</body></html>
<?}?>
