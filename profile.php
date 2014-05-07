<?
include("inc/config.php");
if(isset($_GET['id']) && $_GET['id']!=''){
 $id=$_GET['id'];
}else{
 $id=$who;
}
if($id==$who && !$lg){
 ch();
}
if(!isset($_GET['id']) && !$lg){
 ch(true);
}
if($_SERVER['SCRIPT_NAME']!="/index.php" && $id!=$who){
 redirect("http://open.subinsb.com/$id");
}
$sql=$db->prepare("SELECT * FROM users WHERE id=?");
$sql->execute(array($id));
if($sql->rowCount()==0){
 ser();
}
$plnmsg="Private";
function age($birthday){
 list($day,$month,$year) = explode("/", $birthday);
 $year_diff  = date("Y") - $year;
 $month_diff = date("m") - $month;
 $day_diff   = date("d") - $day;
 if($day_diff < 0 && $month_diff==0){
  $year_diff--;
 }
 if($day_diff < 0 && $month_diff <0){
  $year_diff--;
 }
 return $year_diff;
}
while($r=$sql->fetch()){
 $name=$r['name'];
 $mail=$r['username'];
 $json=json_decode($r['udata'], true);
 $profVals=array("about", "img", "joined", "birth", "gen", "mail", "add", "phone", "live", "work", "lve", "fb", "tw", "gplus", "pin", "header");
 foreach($profVals as $v){
  $json[$v]=isset($json[$v]) ? $json[$v] : "";
 }
 $img=filt($json["img"]);
 $img=$img=='' ? "http://open.subinsb.com/cdn/img/profile_pics/om":$img;
 $about=$json['about']=="" ? $plnmsg:$json['about'];
 $joined=$json['joined'];
 $bir=$json['birth']=="" ? "DD/MM/YYYY":$json['birth'];
 $age=$json['birth']!="" ? age($bir):$plnmsg;
 $gen=$json['gen']=="" ? $plnmsg:$json['gen'];
 $mail=$json['mail']=="" ? $plnmsg:$json['mail'];
 $add=$json['add']=="" ? $plnmsg:$json['add'];
 $phone=$json['phone']=="" ? $plnmsg:$json['phone'];
 $live=$json['live']=="" ? $plnmsg:$json['live'];
 $work=$json['work']=="" ? $plnmsg:$json['work'];
 $lve=$json['lve']=="" ? $plnmsg:$json['lve'];
 $fb=$json['fb']=="" ? $plnmsg:$json['fb'];
 $tw=$json['tw']=="" ? $plnmsg:$json['tw'];
 $gplus=$json['gplus']=="" ? $plnmsg:$json['gplus'];
 $pin=$json['pin']=="" ? $plnmsg:$json['pin'];
 $himg=$json['header'];
 $himg=$himg=="" ? "http://open.subinsb.com/cdn/img/headers/00.png":$himg;
}
$pvals=array($about, $bir);
require("$sroot/inc/class.rep.php");
$RP=new ORep();
$Rep=$RP->getRep($id);
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="ac,home,profile,gadget";$fs="ac,profile,time,home,gadget";$t=substr($name,"-1")=='s' ? "$name' Profile":"$name's Profile";include("inc/head.php");?>
 <meta name="oid" value="<?echo$id;?>"/>
 <meta name="type" value="profile"/>
</head><body>
 <?include("inc/header.php");?>
 <div class="content profile">
  <div class="header">
   <img src="<?echo$himg;?>" width="704" height="180"/>
   <div class="holder">
    <?
    echo "<a href=''>".$name."</a>";
    echo foll($id);
    ?>
   </div>
   <?if($id==$who){?>
    <a id="ch_hi" class="button b-white">Change Header Image</a>
    <a id="editBox" class="button b-red" style="margin:2px;position:absolute;bottom:0px;top:80% !important;left:0px;width:150px;text-align:center;">Edit Profile</a>
   <?}?>
  </div>
  <div class="main blocks">
   <div class="clearfix left block">
    <?
    $_GET['part']=isset($_GET['part']) ? $_GET['part']:"";
    if($_GET['part']=="feed"){$_GET['part']="";}
    ?>
    <div class="navigation">
     <part <?if($_GET['part']=="" || $_GET['part']=="feed"){echo"act";}?>>Feed</part>
     <part <?if($_GET['part']=="about"){echo"act";}?>>About</part>
     <part <?if($_GET['part']=="reputation"){echo"act";}?>>Reputation</part>
    </div>
    <div class="noggler" hide id="feed" <?if($_GET['part']==""){echo"show";}?>>
     <?$_POST['user']=$id;include("inc/feed.php");?>
    </div>
    <div class="noggler" hide id="about" <?if($_GET['part']=="about"){echo"show";}?>>
     <div style="display:inline-block;vertical-align:top;width:260px;">
      <div class="basic smallbox">
       <h>Basic</h>
       <it><n>Joined</n><m>:</m><v class="time"><?echo$joined;?></v></it>
       <it editable><n>Gender</n><m>:</m><v><?echo$gen;?></v></it>
       <it editable><n>Birthday</n><m>:</m><v><?echo$bir;?></v></it>
       <it><n>Age</n><m>:</m><v><?echo$age;?></v></it>
       <it editable in="1"><n>About Me</n><m>:</m><v><?echo$about;?></v></it>
      </div>
      <div class="life smallbox">
       <h>Currently</h>
       <it editable><n>Lives At</n><m>:</m><v><?echo$live;?></v></it>
       <it editable><n>Works At</n><m>:</m><v><?echo$work;?></v></it>
       <it editable><n>Loves</n><m>:</m><v><?echo$lve;?></v></it>
      </div>
     </div>
     <div style="display:inline-block;vertical-align:top;width:260px;">
      <div class="contact smallbox">
       <h>Contact</h>
       <it editable><n>E-Mail</n><m>:</m><v><?echo$mail;?></v></it>
       <it editable><n>Phone</n><m>:</m><v><?echo$phone;?></v></it>
       <it editable in="1"><n>Address</n><m>:</m><v><?echo$add;?></v></it>
      </div>
      <div class="profiles smallbox">
       <h>Other Profiles</h>
       <it editable><n>Facebook</n><m>:</m><v><?echo$fb;?></v></it>
       <it editable><n>Twitter</n><m>:</m><v><?echo$tw;?></v></it>
       <it editable><n>Google+</n><m>:</m><v><?echo$gplus;?></v></it>
       <it editable><n>Pinterest</n><m>:</m><v><?echo$pin;?></v></it>
      </div>
     </div>
    </div>
    <div class="noggler" hide id="reputation" <?if($_GET['part']=="reputation"){echo "show";}?>>
     <center style="font-size:30px;height:40px;"><?echo $Rep['total'];?></center>
     <div class="blocks">
      <div class="block" style="width: 49%;">
       <?
       foreach($RP->getTopPosts() as $r){
       ?>
        <div class="blocks item">
         <div class="block rep"><?echo$r['rep'];?></div>
         <a class="block" href="http://open.subinsb.com/view/<?echo$r['id'];?>">Post # <?echo$r['id'];?></a>
        </div>
       <?
       }
       ?>
      </div>
      <div class="block" style="width: 49%;">
       <?
       foreach($RP->getTopComments() as $r){
       ?>
        <div class="blocks item">
         <div class="block rep"><?echo$r['rep'];?></div>
         <a class="block" href="http://open.subinsb.com/view/<?echo$r['pid']."#".$r['id'];?>">
          <?
          $c=$r['cmt'];
          if(strlen($c) > 10){
           echo substr($c, 0, 10);
          }else{
           echo $c;
          }
          /* The above code should be in one line */
          ?>
         </a>
        </div>
       <?
       }
       ?>
      </div>
     </div>
    </div>
   </div>
   <div class="clearfix right block">
    <div class="image">
     <img src="<?echo$img;?>" height="150" width="150"/>
     <?if($id==$who){?><a id="change_picture">Change Picture</a><?}?>
    </div>
    <div class="osin">
     <div class="tlRep">
      <div class="tl" title="Toal Reputation"><?echo $Rep['total'];?></div>
      <div>
       <span title="Post Likes"><?echo $Rep['count']['pst'];?></span>
       <span title="Post Comments"><?echo $Rep['count']['cmt'];?></span>
       <span title="Comment Likes"><?echo $Rep['count']['cmtl'];?></span>
      </div>
     </div>
     <div class="following" style="text-align:center;">
      <?
      $sql=$db->prepare("SELECT fid FROM conn WHERE uid=? LIMIT 6");
      $sql->execute(array($id));
      while($r=$sql->fetch()){
       $f=$r['fid'];
       echo "<a href='".get("ploc", $f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name", $f, false)."' height='32' width='32' src='".get('avatar',$f)."'></a>";
      }
      $sql=$db->prepare("SELECT COUNT(uid) FROM conn WHERE uid=?");
      $sql->execute(array($id));
      $sql=$sql->fetchColumn();
      echo "<div><div>Following</div><b style='font-size:20px;'>{$sql}</b></div>";
      ?>
     </div>
     <div class="followers" style="text-align:center;">
      <?
      $sql=$db->prepare("SELECT uid FROM conn WHERE fid=? LIMIT 6");
      $sql->execute(array($id));
      while($r=$sql->fetch()){
       $f=$r['uid'];
       echo "<a href='".get("ploc", $f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name", $f, false)."' height='32' width='32' src='".get('avatar', $f)."'></a>";
      }
      $sql=$db->prepare("SELECT 1 FROM conn WHERE fid=?");
      $sql->execute(array($id));
      echo "<div><b style='font-size:20px;'>{$sql->rowCount()}</b><div>Followers</div></div>";
      ?>
     </div>
    </div>
   </div>
  </div>
 </div>
 <?include("inc/gadget.php");?>
</body></html>
