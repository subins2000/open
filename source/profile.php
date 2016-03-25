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

/* A function to calculate age by a birth date in YYYY-MM-DD format */
function age($birthday){
  list($year, $month, $day) = explode("-", $birthday);
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
  $about = $json['about'] == "" ? $defaultVal : $json['about'];
  $joined = $json['joined']; // This Field will always be there and cannot be removed
  $birthday = $json['birth'] == "" ? "YYYY-MM-DD" : $json['birth']; // Default value for birth is DD/MM/YYYY
  $age = $json['birth'] != "" ? age($birthday): $defaultVal;
  $gender = $json['gen'] == "" ? $defaultVal : $json['gen'];
  $mail = $json['mail'] == ""  ? $defaultVal  : $json['mail'];
  $address = $json['add'] == "" ? $defaultVal : $json['add'];
  $phone = $json['phone'] == "" ? $defaultVal  : $json['phone'];
  $liveIn = $json['live'] == "" ? $defaultVal  : $json['live'];
  $work = $json['work'] == "" ? $defaultVal  : $json['work'];
  $loves = $json['lve'] == "" ? $defaultVal  : $json['lve'];
  $facebook = $json['fb'] == "" ? $defaultVal  : $json['fb'];
  $twitter = $json['tw'] == "" ? $defaultVal  : $json['tw'];
  $gplus = $json['gplus'] == "" ? $defaultVal  : $json['gplus'];
  $pinterest = $json['pin'] == "" ? $defaultVal  : $json['pin'];
  $headerIMG = $json['header'] =="" ? O_URL . "/cdn/img/headers/00.png" : $json['header'];
}

require_once "$docRoot/inc/class.rep.php";
$RP = new ORep($id);
$Rep = $RP->getRep(); // Reputation
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
          <img src="<?php echo $headerIMG;?>" width="100%" height="300" />
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
              <li class="tab"><a href="<?php echo Open::URL("/$id/reputation");?>" class="<?php if($_GET['part'] == "reputation"){echo "active";}?>">Reputation</a></li>
            </ul>
            <div class="noggler" hide id="feed" <?php if($_GET['part']==""){echo"show";}?>>
              <?php $_POST['user'] = $id;include "$docRoot/inc/feed.php";?>
            </div>
            <div class="noggler" hide id="about" <?php if($_GET['part']=="about"){echo"show";}?>>
              <table>
                <thead>
                  <tr>
                    <th>Basic Info</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Joined</td>
                    <td class="time"><?php echo $joined;?></td>
                  </tr>
                  <tr>
                    <td data-label="gender">Gender</td>
                    <td data-value="<?php echo $gender;?>"><?php echo $gender;?></td>
                  </tr>
                  <tr editable>
                    <td data-label="birthday">Birthday</td>
                    <td <?php echo $birthday != "YYYY-MM-DD" ? "'" : "";?> data-value="<?php echo $birthday;?>"><?php echo $birthday;?></td>
                  </tr>
                  <tr>
                    <td>Age</td>
                    <td><?php echo $age;?></td>
                  </tr>
                  <tr editable data-textarea="1">
                    <td data-label="aboutme">About Me</td>
                    <td data-value="<?php echo $about;?>"><?php echo $about;?></td>
                  </tr>
                </tbody>
                <thead>
                  <tr>
                    <th>Present</th>
                  </tr>
                </thead>
                <tbody>
                  <tr editable>
                    <td data-label="livesin">Lives In</td>
                    <td data-value="<?php echo $liveIn;?>"><?php echo $liveIn;?></td>
                  </tr>
                  <tr editable>
                    <td data-label="worksat">Works At</td>
                    <td data-value="<?php echo $work;?>"><?php echo $work;?></td>
                  </tr>
                  <tr editable>
                    <td data-label="loves">Loves</td>
                    <td data-value="<?php echo $loves;?>"><?php echo $loves;?></td>
                  </tr>
                </tbody>
                <thead>
                  <tr>
                    <th>Contact</th>
                  </tr>
                </thead>
                <tbody>
                  <tr editable>
                    <td data-label="e-mail">E-Mail</td>
                    <td data-value="<?php echo $mail;?>"><?php echo $mail;?></td>
                  </tr>
                  <tr editable>
                    <td data-label="phone">Phone</td>
                    <td data-value="<?php echo $phone;?>"><?php echo $phone;?></td>
                  </tr>
                  <tr editable>
                    <td data-label="address">Address</td>
                    <td data-value="<?php echo $address;?>"><?php echo $address;?></td>
                  </tr>
                </tbody>
                <thead>
                  <tr>
                    <th>Profiles</th>
                  </tr>
                </thead>
                <tbody>
                  <tr editable>
                    <td data-labe="facebook">Facebook</td>
                    <td data-value="<?php echo $facebook;?>"><?php
                      if($facebook != "Private"){
                        echo "<a target='_blank' href='https://www.facebook.com/". $facebook ."'><img src='//cdn3.iconfinder.com/data/icons/picons-social/57/46-facebook-64.png' /></a>";
                      }else{
                        echo $facebook;
                      }
                  ?></td>
                  </tr>
                  <tr editable>
                    <td data-label="twitter">Twitter</td>
                    <td data-value="<?php echo $twitter;?>"><?php
                      if($twitter != "Private"){
                        echo "<a target='_blank' href='https://www.twitter.com/". $twitter ."'><img src='//cdn3.iconfinder.com/data/icons/picons-social/57/43-twitter-64.png' /></a>";
                      }else{
                        echo $twitter;
                      }
                  ?></td>
                  </tr>
                  <tr editable>
                    <td data-label="google+">Google+</td>
                    <td data-value="<?php echo $gplus;?>"><?php
                      if($gplus != "Private"){
                        echo "<a target='_blank' href='https://plus.google.com/+". $gplus ."'><img src='//cdn3.iconfinder.com/data/icons/picons-social/57/80-google-plus-64.png' /></a>";
                      }else{
                        echo $gplus;
                      }
                  ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="noggler" hide id="reputation" <?php if($_GET['part']=="reputation"){echo "show";}?>>
              <table>
                <tbody>
                  <tr>
                    <td>Post Reputation</td>
                    <td><?php echo $RP->getPostRep();?></td>
                  </tr>
                  <tr>
                    <td>Comment Reputation</td>
                    <td><?php echo $RP->getCommentRep();?></td>
                  </tr>
                  <tr>
                    <td>Comment Likes Reputation</td>
                    <td><?php echo $RP->getCommentLikeRep();?></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td></td>
                    <td><a style="font-size:30px;height:40px;"><?php echo $Rep['total'];?></a></td>
                  </tr>
                </tfoot>
              </table>
              <div class="collection">
                <?php
                $topComments = $RP->getTopComments();
                if(count($topComments) !== 0){
                  echo "<a class='collection-header'><h4>Top Comments</h4></a>";
                }
                foreach($topComments as $r){
              ?>
                  <a href="<?php echo O_URL . "/view/{$r['pid']}#{$r['id']}";?>" class="collection-item truncate">"<?php echo $r['cmt'];?>"<span class="badge"><?php echo $r['rep'];?></span></a>
                <?php
                }
              ?>
              </div>
              <div class="collection">
                <?php
                $topPosts = $RP->getTopPosts();
                if(count($topPosts) !== 0){
                  echo "<a class='collection-header'><h4>Top Posts</h4></a>";
                }
                foreach($topPosts as $r){
              ?>
                  <a href="<?php echo O_URL;?>/view/<?php echo $r['id'];?>" class="collection-item">Post # <?php echo $r['id'];?><span class="badge"><?php echo $r['rep'];?></span></a>
                <?php
                }
              ?>
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
