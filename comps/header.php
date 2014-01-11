<header>
 <div class="logo" style="display: inline-block;">
  <a href='//open.subinsb.com' style='color:white;'><h1 style="float:left;margin-top:5px;">&nbsp;&nbsp;Open</h1></a>
  <span>An Open Source<br/>Social Network</span>
 </div>
 <nav>
  <?if($lg){?>
   <a href="//open.subinsb.com/home"><button class="b-white home" title="Home">Home</button></a>
   <a href="//open.subinsb.com/search"><button class="b-white search" title="Search">Search</button></a>
   <a href="//open.subinsb.com/find"><button class="b-white find" title="Find">Find</button></a>
   <div style="position: absolute;right: 50px;top: 28px;">
    <button id="name_button" rid="<?echo$who;?>"><?echo$uname;?></button>
    <div id="short_profile" class="c_c">
     <div class="left">
      <b><?$uname=explode(" ",$uname);echo$uname[0];?></b><br/>
      <a href="//open.subinsb.com/profile"><button style="margin-top:20px;">View Profile</button></a>
     </div>
     <div class="right">
      <a id="change_picture">Change Picture</a>
      <img src="<?echo$uimg;?>" height="100" width="100"/>
     </div>
     <div class="bottom">
      <a href="//open.subinsb.com/me"><button style="position:absolute;left: 10px;top:3px;">Account</button></a>
      <a href="//open.subinsb.com/login?logout=true"><button style="position:absolute;right: 10px;top:3px;" class="b-red">Sign Out</button></a>
     </div>
    </div>
   </div>
  <?}else{?>
   <a href="//open.subinsb.com/login"><button class="b-red">Sign In</button></a>
   <a href="//open.subinsb.com/register"><button class="b-blue">Sign Up</button></a>
  <?}?>
 </nav>
</header>
