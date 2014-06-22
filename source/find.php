<?

$OP->init();
if($_SERVER['SCRIPT_NAME']=="/find.php" && isset($_GET['q'])){/* We don't want find?q= URLs anymore */
 $_GET['q']=str_replace(array('%2F', '%5C'), array('%252F', '%255C'), urlencode($_GET['q']));
 $To=$_GET['q']=="" ? "":"/{$_GET['q']}";
 $OP->$OP->redirect("/find$To", 301); /* See $OP->redirect() in config.php */
}
?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("", "ac,time,gadget", "ac,gadget");?>
</head><body>
 <?
 $OP->inc("inc/header.php");
 $_GET['q']=isset($_GET['q']) ? $_GET['q']:"";
 $_GET['q']=str_replace(array('%5C', '/'), array('%255C', '%252F'), $_GET['q']);
 $q=$OP->format($_GET['q']);
 ?>
 <div class="content">
  <h1>Find People</h1>
  <p>Here are some of the users of <b>Open</b>. You can search for a specific user using the form below. <b>You wouldn't get any results if you search yourself.</b></p><cl/>
  <form action="/find">
   <span>Search </span><input type="text" name="q" value="<?echo $q;?>" size="35"/>
  </form>
  <cl/>
  <?
  $_GET['p']=!isset($_GET['p']) || $_GET['p']=="" ? 1:$_GET['p'];
  $p=$_GET['p'];
  if($q!='' && $p=='1'){
   $sql=$OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT 10");
   $sql->execute(array(":who"=>$who, ":q"=>"%$q%"));
  }elseif($p!="1"){
   $start=($p-1)*10;
   $limit=10;
   if($q==""){
    $sql=$OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT :start,:limit");
    $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sql->bindValue(':start', $start, PDO::PARAM_INT);
    $sql->bindValue(':who', $who);
    $sql->execute();
   }else{
    $sql=$OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id LIMIT :start,:limit");
    $sql->bindValue(':limit', $limit, PDO::PARAM_INT);
    $sql->bindValue(':start', $start, PDO::PARAM_INT);
    $sql->bindValue(':who', $who);
    $sql->bindValue(':q', "%$q%");
    $sql->execute();
   }
  }else{
   $sql=$OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id LIMIT 10");
   $sql->execute(array(":who"=>$who));
  }
  if($sql->rowCount()==0){
   if($q==''){
    $OP->ser("No Person Found !", "No Person was found.");exit;
   }else{
    $OP->ser("No Person Found !", "No Person was found with the name you searched for.");exit;
   }
  }
  $OR=new ORep();
  while($r=$sql->fetch()){
   $id=$r['id'];
   $name=get("name", $id,false);
   $img=get("img", $id);
   $loc=get("ploc", $id);
   $live=get("live", $id);
   $obirth=str_replace("/", "-",get("birth", $id));
   $birth=date("Y-m-d H:i:s",strtotime($obirth));
   $foll=$OP->dbh->prepare("SELECT COUNT(uid) FROM conn WHERE fid=?");
   $foll->execute(array($id));
   $foll=$foll->fetchColumn();
   $rep=$OR->getRep($id);
  ?>
  <div class="blocks">
   <div class="blocks" style="padding:5px;margin:5px 0px;">
    <div style='background:black;width:100px;height:100px;display:inline-block;vertical-align:top;'>
     <a href="<?echo$loc;?>">
      <center><img style='max-width:100px;max-height:100px;' src="<?echo$img;?>"/></center>
     </a>
    </div>
    <div class="block" style="margin-left:5px;">
     <div><a href="<?echo$loc;?>"><strong style='font-size:18px;'><?echo$name;?></strong></a></div>
     <div field style='font-size:17px;' title="Reputation"><b><?echo$rep['total'];?></b></div>
     <?if($live!=""){?>
      <div field>Lives In <?echo $live;?></div>
     <?}?>
     <div field>Joined <span class="time"><?echo get("joined", $id);?></span></div>
     <?if($obirth!=""){?>
      <div field>Born <span class="time"><?echo $birth;?></span></div>
     <?}?>
     <div field><strong><?echo$foll;?></strong> Followers</div>
     <?echo $OP->followButton($id);?>
    </div>
   </div>
  </div>
  <?
  }
  if($q==''){
   $count=$OP->dbh->prepare("SELECT id FROM users WHERE id!=:who ORDER BY id");
   $count->execute(array(":who"=>$who));
  }else{
   $count=$OP->dbh->prepare("SELECT id FROM users WHERE name LIKE :q AND id!=:who ORDER BY id");
   $count->execute(array(":who"=>$who, ":q"=>"%$q%"));
  }
  $count=$count->rowCount();
  $countP=(ceil($count/10)) + 1;
  $tW=($countP*84) + $countP;
  echo"<center style='overflow-x:auto;margin-top:10px;padding-bottom:10px;'>";
   echo"<div style='width:".$tW."px'>";
    for($i=1;$i<$countP;$i++){
     $isC=$i==$_GET['p'] ? "class='b-green'":"";
     echo "<a href='?p=$i&q=$q'><button $isC>$i</button></a>";
    }
   echo"</div>";
  echo"</center>";
  echo"<cl/>$count Results Found.";
  ?>
  <style>div[field]{margin:5px;}</style>
 </div>
 <?$OP->inc("inc/gadget.php");?>
</body></html>
