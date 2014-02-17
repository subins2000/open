<?
include("config.php");
$sql=$db->prepare("SELECT id FROM users WHERE id NOT IN (SELECT fid FROM conn WHERE uid=:who) AND id IN (SELECT fid FROM conn WHERE uid IN (SELECT fid FROM conn WHERE uid=:who)) AND id!=:who LIMIT 5");
$sql->execute(array(":who"=>$who));
if($sql->rowCount()==0){
 $sql=$db->prepare("SELECT id FROM users WHERE id NOT IN (SELECT fid FROM conn WHERE uid=:who) AND id!=:who LIMIT 5");
 $sql->execute(array(":who"=>$who));
}
if($sql->rowCount()!=0){
 echo'<div class="suggestions" style="padding: 5px 0px;margin: 5px -10px 0px -15px;color: black;"><h2>Suggestions</h2>';
 while($r=$sql->fetch()){
  $u=$r['id'];
  $nm=get("name",$u,false);
  $snm=get("fname",$u,false);
  echo"<div class='sugg'>";
   echo"<div style='vertical-align:top;display:inline-block;'>";
    echo"<a href='".get("plink",$u)."'>";
     echo"<img height='32' width='32' src='".get("avatar",$u)."'/>";
    echo"</a>";
   echo"</div>";
   echo"<div style='vertical-align:top;display:inline-block;'>";
    echo"<a title='$nm' href='".get("plink",$u)."' style='padding-left:5px;'>$snm</a><br/><cl/>";
    echo foll($u);
   echo"</div>";
  echo"</div>";
 }
 echo "<center><cl/><a href='find'>See More Suggestions</a></center>";
 echo'</div>';
 echo'<style>.sugg{margin:5px;padding-top:5px;}</style>';
}
?>
