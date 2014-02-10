<?
include("../comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?$t="Connections - Manage Account";include("../comps/head.php");?>
</head><body>
 <?include("../comps/header.php");?>
 <div class="content">
  <h1>Manage Connections</h1>
  You can manage all your connections at this page. You can see who follows you, who you're following here.<cl/>
  <h2>Following</h2>
  <?
  function parse_foll($id){
   $img=get("img",$id);
   $nm=get("name",$id,false);
   $md=strlen($nm)>=34 ? " ..":"";
   $snm=str_split($nm,34);
   $snm=$snm[0].$md;
   $h="<div style='background:black;padding:5px;display:inline-block;width:150px;height:150px;margin:5px;position:relative;vertical-align:top;'>";
   $h.="<div style='position:absolute;top:0px;right:0px;text-align:left;'>".foll($id)."</div>";
   $h.="<a href='".get("ploc",$id)."'><img style='max-width:150px;max-height:150px;' src='{$img}'/></a>";
   $h.="<div style='position:absolute;bottom:0px;left:0px;right:0px;background:white;text-align:left;padding:5px;'><a href='".get("ploc",$id)."' title='$nm'>$snm</a></div>";
   $h.="</div>";
   echo$h;
  }
  $sql=$db->prepare("SELECT * FROM users WHERE id!=:who AND id IN (SELECT fid FROM conn WHERE uid=:who)");
  $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  if($sql->rowCount()==0){
   if($q==''){sss("You're Lonely","Friends make everything better. Follow Some Persons to enjoy <b>Open</b> more.");exit;}
  }
  echo"<center>";
  while($r=$sql->fetch()){  
   parse_foll($r['id']);
  }
  echo"</center>";
  ?>
  <h2>Followers</h2>
  <?
  $sql=$db->prepare("SELECT * FROM users WHERE id!=:who AND id IN (SELECT uid FROM conn WHERE fid=:who)");
  $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  if($sql->rowCount()==0){
   if($q==''){sss("No One Found","No one is following you. Sorry.");exit;}
  }
  echo"<center>";
  while($r=$sql->fetch()){  
   parse_foll($r['id']);
  }
  echo"</center>";
  ?>
  <h2>Friends</h2>
  Friends are people who follow you and you follow them.<cl/>
  <?
  $sql=$db->prepare("SELECT * FROM users WHERE id!=:who AND id IN (SELECT uid FROM conn WHERE fid=:who AND uid IN (SELECT fid FROM conn WHERE uid=:who))");
  $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  if($sql->rowCount()==0){
   if($q==''){sss("You're Lonely","Friends make everything better. Follow Some Persons to enjoy <b>Open</b> more.");exit;}
  }
  echo"<center>";
  while($r=$sql->fetch()){  
   parse_foll($r['id']);
  }
  echo"</center>";
  ?>
 </div>
</body></html>
