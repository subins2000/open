<?php
\Fr\LS::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("", "ac,time,gadget", "ac,gadget");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content notifications">
        <h1>Notifications</h1>
        <?php
        require_once "$docRoot/inc/render.php";
        $sql = $OP->dbh->prepare("SELECT `id` FROM `notify` WHERE `uid`=? ORDER BY `id` DESC");
        $sql->execute(array($who));
        if($sql->rowCount()==0){
          $OP->ser("No Notifications", "You don't have any notifications.");
        }else{
          $nfs = "";
          while($r=$sql->fetch()){
            $nfs .= Render::notification($r['id']);
          }
          $nfs=str_replace('"', '\"', $nfs);
        }
        echo $nfs;
        ?>
        <p>Notifications older than 50 days are automatically removed</p>
      </div>
    </div>
    <?php include "$docRoot/inc/gadget.php";?>
  </body>
</html>
