<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="navbar-fixed">
  <nav class="row">
    <div class="nav-wrapper col s12">
      <?php
      if(loggedIn){
      ?>
        <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">view_headline</i></a>
      <?php
      }
      ?>
      <a href='<?php echo O_URL ;?>' class="brand-logo left col s4">Open</a>
      <?php
      if(!loggedIn){
      ?>
        <ul class="right">
          <li><a href="<?php echo O_URL ;?>/login<?php
            if( isset($_SERVER['REQUEST_URI']) ){ 
              if( $_SERVER['REQUEST_URI'] != "/" && !preg_match("/\/login/", $_SERVER['REQUEST_URI']) ){
                echo "?c=";
                echo $_SERVER['REQUEST_URI'];
              }
            }
            ?>" class="btn red">Log In</a></li>
          <li><a href="<?php echo O_URL ;?>/register" class="btn blue">Register</a></li>
        </ul>
      <?php
      }
      if(loggedIn){
        $sql = $OP->dbh->prepare("SELECT COUNT(`red`) FROM `notify` WHERE `red`='0' AND `uid`=?");
        $sql->execute(array($who));
        $count = $sql->fetchColumn();
        $count = $count == "" ? 0 : $count;
      ?>
        <div class="right">
          <a id="name_button" class="btn white" who="<?php echo $who;?>"><?php echo $uname;?></a>
          <a id="nfn_button" class="btn <?php echo $count == 0 ? "white" : "red";?>"><?php echo $count;?></a>
        </div>
      <?php
      }
      ?>
    </div>
  </nav>
</div>
<?php
if(loggedIn){
?>
  <ul id="slide-out" class="side-nav fixed">
    <li>
      <a href="<?php echo O_URL;?>">
        <i class="material-icons">home</i>
        <span>Home</span>
      </a>
    </li>
    <li>
      <a href="<?php echo get("plink");?>">
        <i class="material-icons">account_circle</i>
        <span>Profile</span>
      </a>
    </li>
    <li>
      <a href="<?php echo O_URL;?>/find">
        <i class="material-icons">supervisor_account</i>
        <span>People</span>
      </a>
    </li>
    <li>
      <a href="<?php echo O_URL;?>/search">
        <i class="material-icons">trending_up</i>
        <span>Trending</span>
      </a>
    </li>
    <li id="trending">
      <ul id="trends" class="collection">
        <?php
        $sql = $OP->dbh->query("SELECT * FROM `trend` ORDER BY `hits` DESC LIMIT 9");
        foreach($sql as $trend){
          $title = $OP->format( $trend['title'] );
          $url = Open::URL( "search/" . Open::encodeQuery($trend['title']) );
          $extra = strlen($title) >= 25 ? "..." : "";
          $sTitle = str_split($title, 25);
          $sTitle = $sTitle[0] . $extra;
          echo '<li class="collection-item">';
            echo '<a href="'. $url .'" title="'. $title .'">'. $sTitle .'</a>';
          echo "</li>";
        }
        $OP->dbh->query("DELETE FROM `trend` WHERE `hits` = (SELECT MIN(`hits`) FROM (SELECT * FROM `trend` HAVING COUNT(`hits`) > 150) x);");
        ?>
      </ul>
    </li>
  </ul>
  <div id="short_profile" class="c_c row">
    <div class="row">
      <div class="col s12">
        <div class="card blue-grey darken-1 card-small">
          <div class="card-content white-text">
            <div class="col s6">
              <a href="<?php echo get('plink');?>">
              <span class="card-title"><?php
                /* Show the first name only */
                echo get("fname");
              ?></span>
              <p><?php
                if(!class_exists("ORep")){
                  require_once "$docRoot/inc/class.rep.php";
                }
                $HRep = new ORep();
                $HRep = $HRep->getRep($who);
                echo $HRep['total'];                  
              ?></p>
            </div>
              <img src="<?php echo $uimg;?>" class="col s6" />
            </a>
          </div>
          <div class="card-action">
            <a href="<?php echo O_URL ;?>/me">My Account</a>
            <a href="<?php echo O_URL ;?>/login?logout=true">Log Out</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="notifications">
    <div id="nfn" class="c_c">
      <center class="loading"><br/><br/><img src="<?php echo O_URL . "/cdn/img/load.gif";?>"/><br/>Loading</center>
      <div class="nfs"></div>
    </div>
  </div>
<?php
}
?>
