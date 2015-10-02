<?php
\Fr\LS::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Connections - Manage Account");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <h1>Manage Connections</h1>
        <p>You can manage all your connections at this page. You can see who follows you, who you're following here.</p><cl/>
        <h2>Following</h2>
        <?php
        function parseFollower($id){
          global $OP;
          $avatar = get("img", $id);
          $name = get("name", $id,false);
          $pLink = get("ploc", $id);
          $extra = strlen($name) >= 34 ? " .." : "";
          $shortName = str_split($name, 34);
          $shortName = $shortName[0] . $extra;
          
          $html = "<div style='background:black;padding:5px;display:inline-block;width:150px;height:150px;margin:5px;position:relative;vertical-align:top;text-align:center;'>";
            $html .= "<div style='position:absolute;top:0px;right:0px;text-align:left;'>". $OP->followButton($id) ."</div>";
            $html .= "<a href='". $pLink ."'><img style='max-width:150px;max-height:150px;' src='{$avatar}'/></a>";
            $html .= "<div style='position:absolute;bottom:0px;left:0px;right:0px;background:white;text-align:left;padding:5px;'><a href='". $pLink ."' title='$name'>$shortName</a></div>";
          $html .= "</div>";
          echo $html;
        }
        $sql = $OP->dbh->prepare("SELECT * FROM `users` WHERE `id` != :who AND `id` IN (SELECT `fid` FROM `conn` WHERE `uid` = :who)");
        $sql->execute(array(":who" => $who));
        if($sql->rowCount() == 0){
          $OP->sss("You're Lonely", "Friends make everything better. Follow Some Persons to enjoy <b>Open</b> more.");
          exit;
        }
        while($r = $sql->fetch()){  
          parseFollower($r['id']);
        }
        ?>
        <h2>Followers</h2>
        <?php
        $sql = $OP->dbh->prepare("SELECT * FROM `users` WHERE `id` != :who AND `id` IN (SELECT `uid` FROM `conn` WHERE `fid` = :who)");
        $sql->execute(array(":who" => $who));
        if($sql->rowCount() == 0){
          $OP->sss("No One Found", "No one is following you. Sorry.");
          exit;
        }
        while($r = $sql->fetch()){  
          parseFollower($r['id']);
        }
        ?>
        <h2>Friends</h2>
        <p>Friends are people who follow you and you follow them.</p><cl/>
        <?php
        $sql = $OP->dbh->prepare("SELECT * FROM users WHERE id!=:who AND id IN (SELECT uid FROM conn WHERE fid=:who AND uid IN (SELECT fid FROM conn WHERE uid=:who))");
        $sql->execute(array(":who"=>$who));
        if($sql->rowCount()==0){
          $OP->sss("You're Lonely", "Friends make everything better. Follow Some Persons to enjoy <b>Open</b> more.");
          exit;
        }
        while($r = $sql->fetch()){  
          parseFollower($r['id']);
        }
        ?>
      </div>
    </div>
  </body>
</html>