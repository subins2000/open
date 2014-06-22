<?
if($_SERVER['PHP_SELF']=="/search"){
 $_GET['q']=isset($_GET['q']) ? $_GET['q']:"";
 $searchQuery=$OP->format($_GET['q']);
 if($searchQuery!=''){
  $sql=$OP->dbh->prepare("UPDATE trend SET hits=hits+1 WHERE title=?");
  $sql->execute(array($searchQuery));
  if($sql->rowCount()==0){
   $sql=$OP->dbh->prepare("INSERT INTO trend(title,hits)VALUES(?,'1')");
   $sql->execute(array($searchQuery)); 
  }
 }
}
?>
<div style="padding: 0px 15px;margin: 0px -10px 0px -15px;">
 <font size="5" style="color:#74ACE9;background: url(<?echo HOST;?>/source/cdn/img/up.png);background-position: left;background-size: 22px;background-repeat: no-repeat;padding-left: 28px;">Trending</font>
 <div style="padding-left:10px;margin-top:10px;color:black;">
  <?
  $sql=$OP->dbh->query("SELECT * FROM trend ORDER BY hits DESC LIMIT 9");
  foreach($sql as $r){
   echo '<div style="padding:1px;">';
   $wdot=strlen($r['title'])>=14 ? "....":"";
   $sp_t=str_split($r['title'],14);
   echo '<a href="../search?q='.rawurlencode($r['title']).'" title="'.$r['title'].'">'.$sp_t[0]."$wdot</a>";
   echo '<div style="margin-top:5px;"></div>';
   echo "</div>";
  }
  $OP->dbh->query("DELETE FROM trend WHERE hits=(SELECT MIN(hits) FROM (SELECT * FROM trend HAVING COUNT(hits)>150) x);");
  ?>
 </div>
</div>
<div style="background:#FAB359;border-radius: 10px;text-align:center;padding:5px;margin:5px 0px 0px 0px;">
 <a href="invite">Invite your Friends</a>
</div>