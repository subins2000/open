<?
include("comps/config.php");
ch();
?>
<!DOCTYPE html>
<html><head>
 <?include("comps/head.php");?>
</head><body>
 <?include("comps/header.php");?>
 <div class="content">
  <h1>Find People</h1>
  Here are some of the users of <b>Open</b>. You can search for a specific user using the form below. <b>You wouldn't get any results if you search yourself.</b><cl/>
  <form method="GET" action="find" style="border-bottom:1px solid black;padding-bottom:5px;">
   <span>Search :</span><input type="text" name="q" size="35"/>
  </form>
  <cl/>
  <?
  $q=filt($_GET['q']);
  if($q==''){
   $sql=$db->prepare("SELECT * FROM users WHERE id NOT IN(SELECT fid FROM conn WHERE uid=:who) AND id!=:who");
   $sql->execute(array(":who"=>$who));
  }else{
   $sql=$db->prepare("SELECT * FROM users WHERE name LIKE :q AND id!=:who");
   $sql->execute(array(":who"=>$who,":q"=>"%$q%"));
  }
  if($sql->rowCount()==0){
   if($q==''){sss("No Person Found !","You followed every single homosapien who joined <b>Open</b>");exit;}
  }
  echo"<div style='overflow-x:auto;height:190px;'>";
   echo"<div style='width:".($sql->rowCount()*170)."px;'>";
    while($r=$sql->fetch()){
     $udata=json_decode($r['udata'],true);
     $img=get("img",$r['id']);
     echo"<div style='background:black;padding:5px;display:inline-block;width:150px;height:150px;margin:5px;position:relative;vertical-align:top;'>";
      echo"<div style='position:absolute;top:0px;right:0px;text-align:left;'>".foll($r['id'])."</div>";
      echo"<a href='".get("ploc",$r['id'])."'><img style='max-width:150px;max-height:150px;' src='{$img}'/></a>";
      echo"<div style='position:absolute;bottom:0px;left:0px;right:0px;background:white;text-align:left;padding:5px;'><a href='".get("ploc",$r['id'])."'>".$r['name']."</a></div>";
     echo"</div>";
    }
   echo"</div>";
  echo"</div>";
  ?>
 </div>
</body></html>
