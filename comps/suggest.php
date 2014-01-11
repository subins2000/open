<?
include("config.php");
$sql=$db->prepare("SELECT id FROM users WHERE id NOT IN (SELECT fid FROM conn WHERE uid=:who) AND id IN (SELECT fid FROM conn WHERE uid IN (SELECT fid FROM conn WHERE uid=:who)) AND id!=:who LIMIT 5");
$sql->execute(array(":who"=>$who));
if($sql->rowCount()==0){
 $sql=$db->prepare("SELECT id FROM users WHERE id NOT IN (SELECT fid FROM conn WHERE uid=:who) AND id!=:who LIMIT 5");
 $sql->execute(array(":who"=>$who));
}
if($sql->rowCount()!=0){
 echo'<div class="suggestions" style="background: black;border-radius: 10px;padding:5px;margin:5px -10px 0px -15px;"><h2>Suggestions</h2>';
}
while($r=$sql->fetch()){
 $u=$r['id'];
 $nm=get("name",$u,false);
 $md=strlen($nm)>=25 ? "...":"";
 $snm=str_split($nm,25);
 $snm=$snm[0].$md;
 echo"<div class='sugg'>";
  echo"<div style='vertical-align:top;display:inline-block;'>";
   echo"<a href='".get("plink",$u)."'>";
    echo"<img height='32' width='32' src='".get("img",$u)."'/>";
   echo"</a>";
  echo"</div>";
  echo"<div style='vertical-align:top;display:inline-block;'>";
   echo"<a title='$nsm' href='".get("plink",$u)."' style='padding-left:5px;'>$snm</a><br/><cl/>";
   echo foll($u);
  echo"</div>";
 echo"</div>";
}
if($sql->rowCount()!=0){
 echo'</div>';
 echo'<style>.sugg{margin:5px;border-top:1px solid white;padding-top:5px;}</style>';
}
?>
