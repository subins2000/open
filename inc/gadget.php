<?php if(loggedIn){?>
<div class="chatgt">
 <div class="msggt">
  <div class="close">x</div>
  <div class="chattop">
   <a id="cwinopen" href="chat/">Chat</a>
  </div>
  <?php require_once "inc/render.php";?>
  <?php echo Render::chat("gadget");?>
 </div>
  <div class="side-nav fixed" id="users-nav">
    <div class="cusgt">
      <div class="close">x</div>
      <img src="<?php echo get("avatar");?>"/>
      <div class="otd">
        <a href="<?php echo Open::URL("profile");?>"><?php echo get("name", $who,false);?></a><br/>
        <span class="status on"></span><span>Online</span>
      </div>
    </div>
    <div class="users">
      <div style="margin-top: 55px;">
        <?php
        $sql=$OP->dbh->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)");
        $sql->execute(array(":who"=>$who));
        while($r=$sql->fetch()){
          $id=$r['fid'];
          $fname=get("fname", $id,false);
          $name=get("name", $id,false);
          $img=get("avatar", $id);
          $st=get("status", $id);
          echo "<div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $st'>$st</span><span class='name' title='$name'>$fname</span></div>";
        }
        ?>
      </div>
    </div>
  </div>
  <a class="openugt" data-activates="users-nav">Open Chat</a>
</div>
<?php }?>
