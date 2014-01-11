<?
include("comps/config.php");
$tme=time()*60+1200;
$id=1;
$ceb=$_GET['request'];
if(!file_exists("$ceb.php") && !file_exists("$ceb") && $ceb!="" && preg_match("/[1-9]/",$ceb)){
 $_GET['id']=$ceb;
 include("profile.php");
}elseif(!file_exists("$ceb.php") && !file_exists("$ceb") && $ceb!=""){
 ser();
}else{
ch();
?>
<!DOCTYPE html>
<html><head>
 <?include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div style="width:100%;background:rgba(100, 194, 53,.7);margin-top:85px;color:white;">
  <div class="icontent">
   <div class="left">
    <img src="img/logo"/>
   </div>
   <div class="right">
    <h2>Let the Doors Open</h2>
    <p>
     By reading the name Open, you might not get <br/>the idea of this site. Open is a Social<br/>Network like Twitter and Facebok.<br/>&nbsp;&nbsp;&nbsp;The only difference is that Open is <br/> an Open Source Social Network unlike others.
    </p>
   </div>
  </div>
 </div>
 <div style="width:100%;background:white;color:white;text-align:center;">
  <div class="icontent">
   <a href="oauth/login_with_facebook" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(//open.subinsb.com/img/fb_icon) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;">Login With Facebook</a>
   <a href="oauth/login_with_google" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:rgb(231, 38, 54) url(//open.subinsb.com/img/g+_icon) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Login With Google +</a>
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
    <img src="img/ex_code2" width="160"/>
   </div>
  </div>
 </div>
 <div style="width:100%;background:white;">
  <div class="icontent">
   <div class="left">
    <img src="img/ex_post" width="120"/>
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
    <img src="img/ex_code" width="165"/>
   </div>
   <div class="right">
    <h2>Features</h2>
    <p>
     <ol>
      <li>No ADS</li>
      <li>Safe Browsing</li>
      <li>Post To Facebook</li>
      <li>Post To Twitter</li>
      <li>No Vulnerabilities found yet</li>
      <li>Privacy Settings</li>
     </ol>
    </p>
   </div>
  </div>
 </div>
 <div style="width:100%;background:rgba(100, 172, 400,1);color:white;">
  <div class="icontent">
   <div class="left">
    <h2>How Can I contribute ?</h2>
    <p>
     Open is a developing social network. It will take years <br/>for it to be perfect. If you find any flaws/errors or have <br/>a suggestion, please report it on our Project Page.<br/><cl/><font size="5">WE NEED YOUR HELP</font><?include("comps/project_urls.php");?>
    </p>
   </div>
   <div class="right">
    <img src="img/ex_code3" height="179"/>
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
  .right,.left{
   display:inline-block;
   vertical-align:top;
   margin-left:10px;
   line-height:20px;
  }
  .right{
   border-left:1px solid black;
   padding-left:10px;
  }
 </style>
</body></html>
<?}?>
