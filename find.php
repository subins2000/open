<?
include("comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="gadget";$fs="time,gadget";include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content">
  <h1>Find People</h1>
  Here are some of the users of <b>Open</b>. You can search for a specific user using the form below. <b>You wouldn't get any results if you search yourself.</b><cl/>
  <form method="GET" action="find" style="border-bottom:1px solid black;padding-bottom:5px;">
   <span>Search :</span><input type="text" name="q" value="<?echo$_GET['q'];?>" size="35"/>
  </form>
  <cl/>
  <?
  $q=filt($_GET['q']);
  if($q==''){
   $sql=$db->prepare("SELECT id FROM users WHERE id!=:who");
   $sql->execute(array(":who"=>$who));
  }else{
   $sql=$db->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who");
   $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  }
  if($sql->rowCount()==0){
   if($q==''){
    sss("No Person Found !","You followed every single homosapien who joined <b>Open</b>");exit;
   }else{
    ser("No Person Found !","No Person was found with the name you searched for.");exit;
   }
  }
  while($r=$sql->fetch()){
   $id=$r['id'];
   $name=get("name",$id,false);
   $img=get("img",$id);
   $loc=get("ploc",$id);
   $live=get("live",$id);
   $birth=get("birth",$id);
   $foll=$db->prepare("SELECT COUNT(uid) FROM conn WHERE uid=?");
   $foll->execute(array($id));
   $foll=$foll->fetchColumn();
  ?>
   <div style="padding:5px;border-bottom:1px solid white;margin:0px;">
    <div style='background:black;width:100px;height:100px;display:inline-block;vertical-align:top;'>
     <a href="<?echo$loc;?>">
      <center><img style='max-width:100px;max-height:100px;' src="<?echo$img;?>"/></center>
     </a>
    </div>
    <div style="display:inline-block;vertical-align:top;margin-left:5px;">
     <div><a href="<?echo$loc;?>"><strong style='font-size:18px;'><?echo$name;?></strong></a></div>
     <?if($live!=""){?><div field>Lives In <?echo $live;?></div><?}?>
     <div field>Joined <span class="time"><?echo get("joined",$id);?></span></div>
     <?if($birth!=""){?><div field>Born <span class="time"><?echo $birth;?></span></div><?}?>
     <div field><strong><?echo$foll;?></strong> Followers</div>
     <?echo foll($id);?>
    </div>
   </div>
  <?
  }
  ?>
  <style>div[field]{margin:5px;}</style>
 </div>
 <?include("comps/gadget.php");?>
</body></html>
