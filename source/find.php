<?php
\Fr\LS::init();
if ($_SERVER['SCRIPT_NAME'] == "/find.php" && isset($_GET['q'])) {
  /* We don't want find?q= URLs anymore */
  $_GET['q'] = str_replace(array(
    '%2F',
    '%5C'
  ), array(
    '%252F',
    '%255C'
  ), urlencode($_GET['q']));
  $To        = $_GET['q'] == "" ? "" : "/{$_GET['q']}";
  $OP->redirect("/find$To", 301);
  /* See $OP->redirect() in config.php */
}
?>
<!DOCTYPE html>
<html>
  <head>
    <?php
    $OP->head("", "ac,time,gadget", "ac,gadget,home");
    ?>
  </head>
  <body>
    <?php
    include "$docRoot/inc/header.php";
    $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : "";
    $_GET['q'] = str_replace(array(
      '%5C',
      '/'
    ), array(
      '%255C',
      '%252F'
    ), $_GET['q']);
    $q         = $OP->format($_GET['q']);
    ?>
    <div class="wrapper">
      <div class="content">
        <h1>Find People</h1>
        <p>Here are some of the users of <b>Open</b>. You can search for a specific user using the form below.</p><cl/>
        <form action="/find" style='margin: 20px;'>
          <span>Search </span><input type="text" name="q" value="<?php echo $q;?>" size="35"/>
        </form><cl/>
        <?php
        $limit = 10;
        $_GET['p'] = !isset($_GET['p']) || $_GET['p'] == "" ? 1 : $_GET['p'];
        $p         = $_GET['p'];
        if ($q != '' && $p == '1') {
          $sql = $OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT :limit");
          $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
          $sql->execute(array(
            ":who" => $who,
            ":q" => "%$q%"
          ));
        }else if ($p != "1") {
          $start = ($p - 1) * 10;
          if ($q == "") {
            $sql = $OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT :start, :limit");
            $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
            $sql->bindValue(':start', $start, PDO::PARAM_INT);
            $sql->bindValue(':who', $who);
            $sql->execute();
          } else {
            $sql = $OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT :start,:limit");
            $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
            $sql->bindValue(':start', $start, PDO::PARAM_INT);
            $sql->bindValue(':who', $who);
            $sql->bindValue(':q', "%$q%");
            $sql->execute();
          }
        } else {
          $sql = $OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT 10");
          $sql->execute(array(
            ":who" => $who
          ));
        }
        if ($sql->rowCount() == 0) {
          if ($q == '') {
            $OP->ser("No Person Found !", "No Person was found.");
            exit;
          } else {
            $OP->ser("No Person Found !", "No Person was found with the name you searched for.");
            exit;
          }
        }
        if ($q == '') {
          $total_sql = $OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id");
          $total_sql->execute(array(
            ":who" => $who
          ));
        } else {
          $total_sql = $OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id");
          $total_sql->execute(array(
            ":who" => $who,
            ":q" => "%$q%"
          ));
        }
        $count = $total_sql->rowCount();
        $totalpage = (ceil($count / 10));
        echo "<cl/>$count Results Found.";
        
        $OR = new ORep();
        while ($r = $sql->fetch()) {
          $id     = $r['id'];
          $name   = get("name", $id, false);
          $img    = get("img", $id);
          $loc    = get("ploc", $id);
          $live   = get("live", $id);
          $obirth = str_replace("/", "-", get("birth", $id));
          $birth  = date("Y-m-d H:i:s", strtotime($obirth));
          $foll   = $OP->dbh->prepare("SELECT COUNT(uid) FROM conn WHERE fid=?");
          $foll->execute(array(
            $id
          ));
          $foll = $foll->fetchColumn();
          $rep  = $OR->getRep($id);
        ?>
           <div class="blocks">
             <div class="blocks" style="padding:5px;margin:5px 0px;">
              <div style='background:white;width:100px;height:100px;display:inline-block;vertical-align:top;'>
               <a href="<?php echo $loc;?>">
                <center><img style='max-width:100px;max-height:100px;' src="<?php echo $img;?>"/></center>
               </a>
              </div>
              <div class="block" style="margin-left:5px;">
                <div>
                  <a href="<?php echo $loc; ?>">
                    <strong style='font-size:18px;'><?php echo $name . $OP->followButton($id);?></strong>
                  </a>
                </div>
                <div field style='font-size:17px;' title="Reputation">
                  <b><?php echo $rep['total'];?></b>
                </div>
                <?php
                if ($live != "") {
                ?>
                  <div field>Lives In <?php echo $live;?></div>
               <?php
                }
                $joined = get("joined", $id);
                if ($joined != "") {
          ?>
               <div field>Joined <span class="time"><?php
                  echo $joined;
          ?></span></div>
               <?php
                }
                if ($obirth != "") {
          ?>
               <div field>Born <span class="time"><?php
                  echo $birth;
          ?></span></div>
               <?php
                }
          ?>
              <div field><strong><?php
                echo $foll;
          ?></strong> Followers</div>
               
             </div>
             </div>
            </div>
        <?php
        }
        $lastpage = $totalpage;
        $currentpage = (isset($_GET['p']) ? $_GET['p'] : 1);
        $loopcounter = ( ( ( $currentpage + 5 ) <= $lastpage ) ? ( $currentpage + 5 ) : $lastpage );
        $startCounter =  ( ( ( $currentpage - 5 ) >= 5 ) ? ( $currentpage - 5 ) : 1 );
        
        echo "<center style='overflow-x:auto;margin-top:10px;padding-bottom:10px;'>";
        echo "<ul id='s7e9v' class='pagination'>";
          echo '<a href="?p=1" class="disabled"><i class="material-icons">chevron_left</i></a>';
          for($i = $startCounter; $i <= $loopcounter; $i++){
            $isC = $i == $p ? "class='active'" : "";
            echo "<li $isC><a href='?p=$i&q=$q'>$i</a></li>";
          }
          echo '<a href="?p='.$totalpage.'" class="disabled"><i class="material-icons">chevron_right</i></a>';
        echo "</ul>";
        echo "</center>";
        ?>
        <style>div[field]{margin:5px;}</style>
      </div>
    </div>
    <?php
    include "$docRoot/inc/gadget.php";
    ?>
  </body>
</html>
