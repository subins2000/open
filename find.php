<?
include("comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?$cfs="ac,gadget";$fs="ac,time,gadget";include("comps/head.php");?>
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
  $_GET['p']=$_GET['p']=="" ? 1:$_GET['p'];
  $p=$_GET['p'];
  if($q!='' && $p=='1'){
   $sql=$db->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT 10");
   $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  }elseif($p!="1"){
   $start=($p-1)*10;
   $limit=10;
   if($q==""){
    $sql=$db->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT :start,:limit");
    $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sql->bindValue(':start', $start, PDO::PARAM_INT);
    $sql->bindValue(':who', $who);
    $sql->execute();
   }else{
    $sql=$db->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT :start,:limit");
    $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sql->bindValue(':start', $start, PDO::PARAM_INT);
    $sql->bindValue(':who', $who);
    $sql->bindValue(':q', "%$q%");
    $sql->execute();
   }
  }else{
   $sql=$db->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT 10");
   $sql->execute(array(":who"=>$who));
  }
  if($sql->rowCount()==0){
   if($q==''){
    ser("No Person Found !","No Person was found.");exit;
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
   $foll=$db->prepare("SELECT COUNT(uid) FROM conn WHERE fid=?");
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
  if($q==''){
   $count=$db->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id");
   $count->execute(array(":who"=>$who));
  }else{
   $count=$db->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id");
   $count->execute(array(":who"=>$who,":q"=>"%$q%"));
  }
  $count=$count->rowCount();
  $countP=ceil($count/10);
  $tW=($countP*84) + $countP;
  echo"<center style='overflow-x:auto;margin-top:10px;padding-bottom:10px;'>";
   echo"<div style='width:".$tW."px'>";
    for($i=1;$i<$countP;$i++){
     $isC=$i==$_GET['p'] ? "class='b-green'":"";
     echo "<a href='?p=$i'><button $isC>$i</button></a>";
    }
   echo"</div>";
  echo"</center>";
  echo"<cl/>$count Results Found.";
  ?>
  <style>div[field]{margin:5px;}</style>
 </div>
 <?include("comps/gadget.php");?>
</body></html>
