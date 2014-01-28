<?
if($_SERVER['PHP_SELF']=="/search.php"){
 $q=filt($_GET['q']);
 if($q!=''){
  $sql=$db->prepare("UPDATE trend SET hits=hits+1 WHERE title=?");
  $sql->execute(array($q));
 }
 if($sql->rowCount()==0 && $q!=""){
  $sql=$db->prepare("INSERT INTO trend(title,hits)VALUES(?,'1')");
  $sql->execute(array($q)); 
 }
}
?>
<div style="background: black;border-radius: 10px;padding:15px;margin:5px -10px 0px -15px;">
 <font size="5" style="color:rgb(0,250,120);">Trending</font>
 <div style="color:lightblue;padding-left:10px;margin-top:10px;">
  <?
  $sql=$db->query("SELECT * FROM trend ORDER BY hits DESC LIMIT 9");
  foreach($sql as $r){
   echo '<div style="padding:1px;">';
   $wdot=strlen($r['title'])>=14 ? "....":"";
   $sp_t=str_split($r['title'],14);
   echo '<a href="../search?q='.urlencode($r['title']).'" title="'.$r['title'].'">'.$sp_t[0]."$wdot</a>";
   echo '<div style="margin-top:5px;"></div>';
   echo "</div>";
  }
  $db->query("DELETE FROM trend WHERE hits=(SELECT MIN(hits) FROM (SELECT * FROM trend HAVING COUNT(hits)>150) x);");
  ?>
 </div>
</div>
<div style="background:#FAB359;border-radius: 10px;text-align:center;padding:5px;margin:5px -10px 0px -15px;">
 <a href="invite">Invite your Friends</a>
</div>
