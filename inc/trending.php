<div style="padding: 0px 15px;margin: 0px -10px 0px -15px;">
  <font size="5" style="color:#74ACE9;background: url(<?php echo HOST;?>/cdn/img/up.png);background-position: left;background-size: 22px;background-repeat: no-repeat;padding-left: 28px;">Trending</font>
  <div style="padding-left:10px;margin-top:10px;color:black;">
    <?php
    $sql = $OP->dbh->query("SELECT * FROM `trend` ORDER BY `hits` DESC LIMIT 9");
    foreach($sql as $trend){
      $title = $OP->format( $trend['title'] );
      $url = Open::URL( "search/" . Open::encodeQuery($trend['title']) );
      $extra = strlen($title) >= 25 ? "..." : "";
      $sTitle = str_split($title, 25);
      $sTitle = $sTitle[0] . $extra;
      
      echo '<div style="padding:1px;margin-bottom: 5px;">';
        echo '<a href="'. $url .'" title="'. $title .'">'. $sTitle .'</a>';
      echo "</div>";
    }
    $OP->dbh->query("DELETE FROM `trend` WHERE `hits` = (SELECT MIN(`hits`) FROM (SELECT * FROM `trend` HAVING COUNT(`hits`) > 150) x);");
    ?>
  </div>
</div>
<div style="background:#FAB359;border-radius: 10px;text-align:center;padding:5px;margin:5px 0px 0px 0px;">
  <a href="invite">Invite your Friends</a>
</div>