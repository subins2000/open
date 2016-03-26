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
  <div class="side-nav fixed right-aligned" id="users-nav">
    <div class="cusgt">
      <div class="close">x</div>
      <img src="<?php echo get("avatar");?>"/>
      <div class="otd">
        <a href="<?php echo Open::URL("profile");?>"><?php echo get("name", $who,false);?></a>
        <span class="status on"></span><span>Online</span>
      </div>
    </div>
    <div class="users">
      <div>
        <?php
        $chatUsers = array();
        $sql=$OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid` = :who AND `fid` IN (SELECT `uid` FROM `conn` WHERE `fid` = :who)");
        $sql->execute(array(":who"=>$who));
        while($r=$sql->fetch()){
          $id = $r['fid'];
          $chatUsers[$id] = array(
            "fname" => get("fname", $id, false),
            "name" => get("name", $id, false),
            "avatar" => get("avatar", $id),
            "status" => get("status", $id)
          );
        }
        uasort($chatUsers, function($a, $b) {
          if($a['status'] == "off" && $b['status'] == "on"){
            return 1;
          }else{
            return -1;
          }
        });
        foreach($chatUsers as $id => $user){
          echo "<div class='user' id='$id'><img height='32' width='32' src='{$user['avatar']}'/><span class='status {$user['status']}'>{$user['status']}</span><span class='name' title='{$user['name']}'>{$user['fname']}</span></div>";
        }
        ?>
      </div>
    </div>
  </div>
  <a class="btn openugt" data-activates="users-nav" href="#">Chat</a>
</div>
<?php }?>
