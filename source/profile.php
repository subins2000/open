<?php
/* The ID of the user's profile to show */
if( isset($_GET['id']) ){
  if( $_GET['id'] != '' && $_GET['id'] != 'profile' ){
    $id = $_GET['id'];
  }else{
    $id = $who;
    \Fr\LS::init();
  }
}

/* The tab to show/highlight */
if( isset($_GET['part']) ) {
  $_GET['part'] = $_GET['part'] == "feed" ? "" : $_GET['part'];
}else{
  $_GET['part'] = "";
}

if(!isset($_GET['id']) && !loggedIn){
  \Fr\LS::init();
}

$sql = $OP->dbh->prepare("SELECT * FROM `users` WHERE `id`=?");
$sql->execute(array($id));

/* Check if user exits and the values fiven are valid, else 404 */
if($sql->rowCount() == 0 || ($_GET['part'] != "about" && $_GET['part'] != "reputation" && $_GET['part'] != "" ) === true ){
  $OP->ser();
}

/* A function to calculate age by a birth date in DD/MM/YYYY format */
function age($birthday){
  list($day, $month, $year) = explode("/", $birthday);
  $year_diff = date("Y") - $year;
  $month_diff = date("m") - $month;
  $day_diff = date("d") - $day;
  if($day_diff < 0 && $month_diff==0){
    $year_diff--;
  }
  if($day_diff < 0 && $month_diff <0){
    $year_diff--;
  }
  return $year_diff;
}
$defaultVal = "Private"; // The Default value for fields
while($r = $sql->fetch()){
  $name = $r['name'];
  $mail = $r['username'];
  $json = json_decode($r['udata'], true);
  $profVals = array("about", "img", "joined", "birth", "gen", "mail", "add", "phone", "live", "work", "lve", "fb", "tw", "gplus", "pin", "header");
  foreach($profVals as $v){
    $json[$v] = isset($json[$v]) ? $json[$v] : "";
  }
  $img = $OP->format($json["img"]);
  $img = $img == '' ? Open::URL("cdn/img/avatars/om.png") : $img;
  $about = $json['about'] == "" ? $defaultVal   : $json['about'];
  $joined = $json['joined']; // This Field will always be there and cannot be removed
  $birthday = $json['birth'] == ""   ? "DD/MM/YYYY"   : $json['birth']; // Default value for birth is DD/MM/YYYY
  $age = $json['birth'] != ""   ? age($birthday): $defaultVal;
  $gender = $json['gen'] == ""   ? $defaultVal   : $json['gen'];
  $mail = $json['mail'] == ""   ? $defaultVal  : $json['mail'];
  $address = $json['add'] == ""   ? $defaultVal   : $json['add'];
  $phone = $json['phone'] == ""   ? $defaultVal  : $json['phone'];
  $liveIn = $json['live'] == ""   ? $defaultVal  : $json['live'];
  $work = $json['work'] == ""   ? $defaultVal  : $json['work'];
  $loves = $json['lve'] == ""   ? $defaultVal  : $json['lve'];
  $facebook = $json['fb'] == ""   ? $defaultVal  : $json['fb'];
  $twitter = $json['tw'] == ""   ? $defaultVal  : $json['tw'];
  $gplus = $json['gplus'] == ""   ? $defaultVal  : $json['gplus'];
  $pinterest = $json['pin'] == ""   ? $defaultVal  : $json['pin'];
  $headerIMG = $json['header'] =="" ? O_URL . "/cdn/img/headers/00.png" : $json['header'];
}
require_once "$docRoot/inc/class.rep.php";
$RP = new ORep();
$Rep = $RP->getRep($id); // Reputation
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head($name, "ac,profile,time,home,gadget", "ac,home,profile,gadget");?>
    <meta name="oid" value="<?php echo $id;?>"/>
    <meta name="type" value="profile"/>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content profile">
        <div class="header">
          <img src="<?php echo $headerIMG;?>" width="100%" height="200" />
          <div class="holder">
            <?php
            echo "<a href=''>{$name}</a>"; // Attributing link to current page
            echo $OP->followButton($id);
            ?>
          </div>
          <?php if($id == $who){ // If the profile is of logged in user himself ?>
            <a id="ch_hi" class="btn white">Change Header Image</a>
            <a id="editBox" class="btn red">Edit Profile</a>
          <?php }?>
        </div>
        <div class="main row">
          <div class="col m9 left">
            <ul class="tabs navigation">
              <li class="tab"><a href="<?php echo Open::URL("/$id/feed");?>" class="<?php if($_GET['part'] == "" || $_GET['part'] == "feed"){echo "active";}?>">Feed</a></li>
              <li class="tab"><a href="<?php echo Open::URL("/$id/about");?>" class="<?php if($_GET['part'] == "about"){echo "active";}?>">About</a></li>
              <li class="tab"><a href="<?php echo Open::URL("/$id/reputation");?>" class="<?php if($_GET['part'] == "reputation"){echo "active";}?>"">Reputation</li>
            </ul>
            <div class="noggler" hide id="feed" <?php if($_GET['part']==""){echo"show";}?>>
              <?php $_POST['user'] = $id;include "$docRoot/inc/feed.php";?>
            </div>
            <div class="noggler" hide id="about" <?php if($_GET['part']=="about"){echo"show";}?>>
              <div style="display:inline-block;vertical-align:top;width:320px;">
                <div class="basic smallbox card">
                  <h>Basic</h>
                  <it>
                    <n>Joined</n>
                    <m>:</m>
                    <v class="time"><?php echo $joined;?></v>
                  </it>
                  <it editable>
                    <n>Gender</n>
                    <m>:</m>
                    <v><?php echo $gender;?></v>
                  </it>
                  <it editable>
                    <n>Birthday</n>
                    <m>:</m>
                    <v><?php echo $birthday;?></v>
                  </it>
                  <it>
                    <n>Age</n>
                    <m>:</m>
                    <v><?php echo $age;?></v>
                  </it>
                  <it editable in="1">
                    <n>About Me</n>
                    <m>:</m>
                    <v><?php echo $about;?></v>
                  </it>
                </div>
                <div class="life smallbox">
                  <h>Currently</h>
                  <it editable>
                    <n>Lives In</n>
                    <m>:</m>
                    <v><?php echo $liveIn;?></v>
                  </it>
                  <it editable>
                    <n>Works At</n>
                    <m>:</m>
                    <v><?php echo $work;?></v>
                  </it>
                  <it editable>
                    <n>Loves</n>
                    <m>:</m>
                    <v><?php echo $loves;?></v>
                  </it>
                </div>
              </div>
              <div style="display:inline-block;vertical-align:top;width:275px;">
                <div class="contact smallbox">
                  <h>Contact</h>
                  <it editable>
                    <n>E-Mail</n>
                    <m>:</m>
                    <v><?php echo $mail;?></v>
                  </it>
                  <it editable>
                    <n>Phone</n>
                    <m>:</m>
                    <v><?php echo $phone;?></v>
                  </it>
                  <it editable in="1">
                    <n>Address</n>
                    <m>:</m>
                    <v><?php echo $address;?></v>
                  </it>
                </div>
                <div class="profiles smallbox">
                  <h>Other Profiles</h>
                  <it editable><n>Facebook</n><m>:</m><v><?php echo $facebook;?></v></it>
                  <it editable><n>Twitter</n><m>:</m><v><?php echo $twitter;?></v></it>
                  <it editable><n>Google+</n><m>:</m><v><?php echo $gplus;?></v></it>
                  <it editable><n>Pinterest</n><m>:</m><v><?php echo $pinterest;?></v></it>
                </div>
              </div>
            </div>
            <div class="noggler" hide id="reputation" <?php if($_GET['part']=="reputation"){echo "show";}?>>
              <center style="font-size:30px;height:40px;"><?php echo $Rep['total'];?></center>
              <div class="blocks">
                <div class="block" style="width: 49%;">
                  <?php
                  foreach($RP->getTopPosts() as $r){
                  ?>
                    <div class="blocks item">
                      <div class="block rep"><?php echo $r['rep'];?></div>
                      <a class="block" href="<?php echo O_URL ;?>/view/<?php echo $r['id'];?>">Post # <?php echo $r['id'];?></a>
                    </div>
                  <?php
                  }
                  ?>
                </div>
                <div class="block" style="width: 49%;">
                  <?php
                  foreach($RP->getTopComments() as $r){
                  ?>
                    <div class="blocks item">
                      <div class="block rep"><?php echo $r['rep'];?></div>
                      <a class="block" href="<?php echo Open::URL("view/{$r['pid']}#{$r['id']}");?>">
                        <?php
                        $c = $r['cmt'];
                        if(strlen($c) > 10){
                          echo substr($c, 0, 10);
                        }else{
                          echo $c;
                        }
                        /* The above code should be in one line */
                        ?>
                      </a>
                    </div>
                  <?php
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
          <div class="col m3 right">
            <div class="image">
              <img src="<?php echo $img;?>" class="responsive-img" />
              <?php if($id == $who){?>
                <a id="change_picture">Change Picture</a>
              <?php }?>
            </div>
            <div class="osin">
              <div class="tlRep">
                <div class="tl" title="Toal Reputation"><?php echo $Rep['total'];?></div>
                <div>
                  <span title="Post Likes"><?php echo $Rep['count']['pst'];?></span>
                  <span title="Post Comments"><?php echo $Rep['count']['cmt'];?></span>
                  <span title="Comment Likes"><?php echo $Rep['count']['cmtl'];?></span>
                </div>
              </div>
              <div class="following" style="text-align:center;">
                <?php
                $sql = $OP->dbh->prepare("SELECT `fid` FROM `conn` WHERE `uid` = ? LIMIT 6");
                $sql->execute(array($id));
                while($r = $sql->fetch()){
                  $f = $r['fid'];
                  echo "<a href='".get("ploc", $f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name", $f, false)."' height='32' width='32' src='".get('avatar', $f)."'></a>";
                }
                $sql = $OP->dbh->prepare("SELECT COUNT(1) FROM `conn` WHERE `uid` = ?");
                $sql->execute(array($id));
                echo "<div><div>Following</div><b style='font-size:20px;'>{$sql->fetchColumn()}</b></div>";
                ?>
              </div>
              <div class="followers" style="text-align:center;">
                <?php
                $sql = $OP->dbh->prepare("SELECT `uid` FROM `conn` WHERE fid=? LIMIT 6");
                $sql->execute(array($id));
                while($r=$sql->fetch()){
                  $f=$r['uid'];
                  echo "<a href='".get("ploc", $f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name", $f, false)."' height='32' width='32' src='".get('avatar', $f)."'></a>";
                }
                $sql = $OP->dbh->prepare("SELECT COUNT(1) FROM `conn` WHERE `fid` = ?");
                $sql->execute(array($id));
                echo "<div><b style='font-size:20px;'>{$sql->fetchColumn()}</b><div>Followers</div></div>";
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "$docRoot/inc/gadget.php";?>
  </body>
</html>
