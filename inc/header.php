<header>
 <div class="logo">
  <a href='<?echo HOST;?>' style='color:white;'><h1 style="float:left;margin: 8px 0px 0px;">&nbsp;&nbsp;Open</h1></a>
 </div>
 <nav>
  <?if($OP->lg){?>
   <a href="<?echo HOST;?>/home" class="button b-white home lNav" title="Home">Home</a>
   <a href="<?echo HOST;?>/search" class="button b-white search lNav" title="Search">Search</a>
   <a href="<?echo HOST;?>/find" class="button b-white find lNav" title="Find">Find</a>
   <a href="<?echo HOST;?>/chat" class="button b-white chat lNav" title="Chat">Chat</a>
  <?}else{?>
   <a href="<?echo HOST;?>/login" class="button b-red">Sign In</a>
   <a href="<?echo HOST;?>/register" class="button b-blue">Sign Up</a>
  <?}?>
 </nav>
 <?if($OP->lg){?>
  <div class="curuserinfo">
   <button id="name_button" class="b-white" rid="<?echo$who;?>"><?echo $uname;?></button>
   <div id="short_profile" class="c_c">
    <div class="left">
     <a href="<?echo get('plink');?>">
      <b><?
      /* Show the first name only */
      $uname = explode(" ", $uname); /* First name and last name are separated by white space */
      echo $uname[0]; /* Get the first part */
      ?></b>
     </a><br/>
     <div style="margin-top:15px;font-size:17px;font-weight:bold;" title="Reputation">
      <?
      if(!class_exists("ORep")){
       $OP->inc("inc/class.rep.php");
      }
      $HRep=new ORep();
      $HRep=$HRep->getRep($who);
      echo $HRep['total'];
      ?>
     </div>
    </div>
    <div class="right">
     <a id="change_picture">Change Picture</a>
     <img src="<?echo$uimg;?>" height="100" width="100"/>
    </div>
    <div class="bottom">
     <a href="<?echo HOST;?>/me"><button style="position:absolute;left: 10px;top:4px;">Account</button></a>
     <a href="<?echo HOST;?>/login?logout=true"><button style="position:absolute;right: 10px;top:4px;" class="b-red">Sign Out</button></a>
    </div>
   </div>
  </div>
  <div class="notifications">
   <?
   $sql=$OP->dbh->prepare("SELECT red FROM notify WHERE red='0' AND uid=?");
   $sql->execute(array($who));
   $count=$sql->rowCount();
   ?>
   <button id="nfn_button" class="b-white<?if($count!=0){echo' b-red';}?>"><?echo$count;?></button>
   <div id="nfn" class="c_c">
    <center class="loading"><br/><br/><img src="<?echo HOST;?>/source/cdn/img/load.gif"/><br/>Loading</center>
    <div class="nfs"></div>
   </div>
  </div>
 <?}?>
</header>