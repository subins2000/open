<?
include("comps/config.php");
if($_GET['id']!=''){
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
 header("Location:http://open.subinsb.com/$id");
}
$sql=$db->prepare("SELECT * FROM users WHERE id=?");
$sql->execute(array($id));
if($sql->rowCount()==0){
 ser();
}
$plnmsg="Private";
function age($birthday){list($day,$month,$year) = explode("/",$birthday);$year_diff  = date("Y") - $year;$month_diff = date("m") - $month;$day_diff   = date("d") - $day;if($day_diff < 0 && $month_diff==0){$year_diff--;}if($day_diff < 0 && $month_diff <0){$year_diff--;}return $year_diff;}
while($r=$sql->fetch()){
 $name=$r['name'];
 $mail=$r['username'];
 $json=json_decode($r['udata'],true);
 $img=filt($json["img"]);
 $img=$img=='' ? "http://open.subinsb.com/img/profile_pics/om":$img;
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
}
$pvals=array($about,$bir);
$lks=$db->prepare("SELECT uid FROM likes WHERE uid=?");
$lks->execute(array($id));
$lks=$lks->rowCount();
$cms=$db->prepare("SELECT uid FROM cmt WHERE uid=?");
$cms->execute(array($id));
$cms=$cms->rowCount();
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="ac,home,profile,gadget";$fs="ac,profile,time,home,gadget";$t=substr($name,"-1")=='s' ? "$name' Profile":"$name's Profile";include("comps/head.php");?>
 <meta name="oid" value="<?echo$id;?>"/>
 <meta name="type" value="profile"/>
</head><body>
 <?include("comps/header.php");?>
 <div class="content profile">
  <div class="header">
   <img src="http://open.subinsb.com/img/headers/00.png" width="704" height="134"/>
   <div class="holder">
    <?echo$name.foll($id);?>
   </div>
  </div>
  <div class="main">
   <div class="clearfix left">
    <?if($_GET['part']=="feed"){$_GET['part']="";}?>
    <div class="navigation">
     <part <?if($_GET['part']==""){echo"act";}?>>Feed</part>
     <part <?if($_GET['part']=="about"){echo"act";}?>>About</part>
     <part <?if($_GET['part']=="likes"){echo"act";}?>>Likes</part>
     <part <?if($_GET['part']=="comments"){echo"act";}?>>Comments</part>
    </div>
    <div class="noggler" id="feed" <?if($_GET['part']==""){echo"style='display:block;'";}?>>
     <?$_POST['user']=$id;include("comps/feed.php");?>
    </div>
    <div class="noggler" id="about" <?if($_GET['part']=="about"){echo"style='display:block;'";}?>>
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
    <div class="noggler" id="likes" <?if($_GET['part']=="likes"){echo"style='display:block;'";}?>>
     <?
     $sql=$db->prepare("SELECT liked, pid FROM likes WHERE 
     uid=:id AND pid IN (
      SELECT id FROM posts WHERE (
       privacy='pub' OR (
        privacy='fri' AND uid IN (
         SELECT fid FROM conn WHERE uid=:who AND fid IN (
          SELECT uid FROM conn WHERE fid=:who
         )
        )
       )
      )
     )
     ORDER BY liked DESC LIMIT 10");
     $sql->execute(array(":id"=>$id,":who"=>$who));
     if($sql->rowCount()==0){
      echo "<h1>No Post Likes</h1>";
     }else{
      echo "<h1>Post Likes</h1>";
      while($r=$sql->fetch()){
       echo "<div style='background:gray;color:white;padding:10px 15px;border-radius:10px;border:1px solid white;'><span class='time'>{$r['liked']}</span> : $name liked <a href='view?id={$r['pid']}'>Post #".$r['pid']."</a></div>";
      }
     }
     $sql=$db->prepare("SELECT liked, cid FROM clikes WHERE 
     uid=:id AND cid IN (
      SELECT id FROM cmt WHERE pid IN (
       SELECT id FROM posts WHERE (
        privacy='pub' OR (
         privacy='fri' AND uid IN (
          SELECT fid FROM conn WHERE uid=:who AND fid IN (
           SELECT uid FROM conn WHERE fid=:who
          )
         )
        )
       )
      )
     )
     ORDER BY liked DESC");
     $sql->execute(array(":id"=>$id,":who"=>$who));
     if($sql->rowCount()==0){
      echo "<h1>No Comment Likes</h1>";
     }else{
      echo "<h1>Comment Likes</h1>";
      while($r=$sql->fetch()){
       $cid=$db->prepare("SELECT pid FROM cmt WHERE id=?");
       $cid->execute(array($r['cid']));
       $cid=$cid->fetch();
       $cid=$cid['pid'];
       echo "<div style='background:gray;color:white;padding:10px 15px;border-radius:10px;border:1px solid white;'><span class='time'>{$r['liked']}</span> : $name liked <a href='view?id={$cid}#{$r['cid']}'>Comment # {$r['cid']}</a></div>";
      }
     }
     ?>
     Post & Comment Likes are limited to 10 results.
    </div>
    <div class="noggler" id="comments" <?if($_GET['part']=="comments"){echo"style='display:block;'";}?>>
     <?
     $sql=$db->prepare("SELECT * FROM cmt WHERE uid=:id AND pid IN (SELECT id FROM posts WHERE (privacy='pub' OR (privacy='fri' AND uid IN (SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who))))) ORDER BY posted DESC LIMIT 10");
     $sql->execute(array(":id"=>$id,":who"=>$who));
     while($r=$sql->fetch()){
      echo "<div style='background:gray;color:white;padding:10px 15px;border-radius:10px;border:1px solid white;'><div class='time'>{$r['posted']}</div><div clear></div><div style='margin-left:10px;'>$name commented on <a href='view?id=".$r['pid']."'>Post #".$r['pid']."</a><div clear></div><div style='border:5px dashed white;padding:10px 15px;'>{$r['cmt']}</div></div></div>";
     }
     if($sql->rowCount()==0){
      echo "<h1>No Comments</h1>";
     }
     ?>
    </div>
   </div>
   <div class="clearfix right">
    <div class="image">
     <img src="<?echo$img;?>" height="150" width="150"/>
     <?if($id==$who){?><a id="change_picture">Change Picture</a><?}?>
    </div>
    <div class="osin">
     <div val><span>Likes</span><c>:</c><v><?echo$lks;?></v></div>
     <div val><span>Comments</span><c>:</c><v><?echo$cms;?></v></div>
     <div class="following" style="text-align:center;">
      <?
      $sql=$db->prepare("SELECT fid FROM conn WHERE uid=? LIMIT 6");
      $sql->execute(array($id));
      while($r=$sql->fetch()){
       $f=$r['fid'];
       echo "<a href='".get("ploc",$f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name",$f,false)."' height='32' width='32' src='".get('img',$f)."'></a>";
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
       echo "<a href='".get("ploc",$f)."'><img style='border-radius: 0.7em;margin-left:2px;' title='".get("name",$f,false)."' height='32' width='32' src='".get('img',$f)."'></a>";
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
 <?if($who==$id){?>
 <button id="editBox" style="margin:0px;position:fixed;top:95px;left:0px;width:200px;text-align:center;">Edit Profile</button>
 <?}?>
 <?include("comps/gadget.php");?>
</body></html>
