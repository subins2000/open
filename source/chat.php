<?php
\Fr\LS::init();
if($_SERVER['SCRIPT_NAME'] == "/chat.php" && isset($_GET['q'])){/* We don't want view?id= URLs anymore */
  $To = $_GET['id'] == "" ? "" : "/{$_GET['id']}";
  $OP->redirect("/chat$To", 301); /* 3rd Param is the status code and not the 2nd */
}
$_GET['id'] = isset($_GET['id']) ? $_GET['id'] : "";
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Messages", "ac,time,gadget", "chat,gadget,ac");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <div class="users">
          <?php
          $sql = $OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (SELECT `uid` FROM `conn` WHERE `fid`=:who) ORDER BY `since`");
          $sql->execute(array(":who" => $who));
          if( $sql->rowCount() == 0 ){
            echo "No Friends found";
          }else{
          while($r = $sql->fetch()){
              $id = $r['fid'];
              $fname = get("fname", $id,false);
              $name = get("name", $id,false);
              $img = get("avatar", $id);
              $status = get("status", $id);
              echo "<a href='/chat/$id'><div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $status'>$status</span><span class='name' title='$name'>$fname</span></div></a>";
            }
          }
          ?>
        </div>
        <?php
        $friendID = $OP->format($_GET['id']);
        if($friendID != ""){
          $sql = $OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid` = :who AND `fid` = :fid AND `uid` IN (SELECT `fid` FROM `conn` WHERE `uid` = :fid)");
          $sql->execute(array(
            ":who" => $who,
            ":fid" => $friendID
          ));
        }
        ?>
        <div class="chat">
          <div class="chatInfo">
            <?php
            if($sql->rowCount() != 0 && $friendID != ""){
              $pLink = get("plink", $friendID);
              $fName = get("name", $friendID, false);
              echo "Chat | <a href='{$pLink}'>{$fName}</a>";
            }
            ?>
          </div>
          <div class="chatLoader">
            <?php
            if($friendID != ""){
              if($sql->rowCount()==0){
                if($friendID == $who){
                  echo "<h4>Uh... It's you</h4><p>Why do you want to chat with yourself ?</p>";
                }else{
                  echo "<h4>Not Friends</h4><p>You and $friendID are not friends.</p>";
                }
              }else{
                require_once "$docRoot/inc/render.php";
                echo Render::chat($friendID);
              }
            }else{
              echo "<h4>No User Selected</h4>To see messages, choose a person seen on the left table.";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>