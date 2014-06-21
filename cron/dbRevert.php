<?
/* This file was used to revert Datas in Database which is no longer used. Preserved for history purposes - 19 May 2014 */
include("../inc/load.php");
function toBack($t){
 $t=htmlspecialchars_decode($t);
 $t=preg_replace("/<span style='white-space:pre-wrap;'>(.*?)<\/span>/", "$1", $t);
 $t=preg_replace("/<span style='white-space:pre-wrap;'>/", "", $t);
 $t=preg_replace("/<\/span>/", "", $t);
 $t=preg_replace("/<b>(.*?)<\/b>/", "**$1**", $t);
 $t=preg_replace("/<i>(.*?)<\/i>/", "*/$1/*", $t);
 $t=preg_replace("/<a(.*?)href=\"(.*?)\">(.*?)<\/a>/", "$3", $t);
 $t=preg_replace("/<a(.*?)href='(.*?)" . HOST . "\/(.*?)'>\@(.*?)<\/a>/", "@$3", $t);
 $t=preg_replace("/<a(.*?)href=\"(.*?)\">(.*?)<\/a>/", "$3", $t);
 return $t;
}
echo "<br/>Doing Post Data Exchange";
ob_flush();
flush();
$sql=$OP->dbh->query("SELECT id, post FROM posts");
while($r=$sql->fetch()){
 $p=$r['post'];
 $i=$r['id'];
 $p=toBack($p);
 $n=$OP->dbh->prepare("UPDATE `posts` SET `post`=? WHERE id=?");
 $n->execute(array($p, $i));
}
echo "<br/>Doing Comment Data Exchange";
ob_flush();
flush();
$sql=$OP->dbh->query("SELECT `id`, `cmt` FROM `cmt`");
while($r=$sql->fetch()){
 $p=$r['cmt'];
 $i=$r['id'];
 $p=toBack($p);
 $n=$OP->dbh->prepare("UPDATE `cmt` SET `cmt`=? WHERE id=?");
 $n->execute(array($p, $i));
}
/*
echo "<br/>Doing JSON Data Exchange";
ob_flush();
flush();
$sql=$OP->dbh->query("SELECT `id`, `udata` FROM `users`");
foreach($sql->fetchAll() as $r){
 $u=$r['id'];
 $j=json_decode($r['udata'], true);
 if(is_array($j)){
  foreach($j as $k=>$v){
   if($v!=""){
    $v=is_array($v) ? json_encode($v):$v;
    $a=$OP->dbh->prepare("UPDATE `udata` SET `value`=? WHERE `keyname`=? AND `uid`=?");
    $a->execute(array($v, $k, $u));
    if($a->rowCount()==0){
     $n=$OP->dbh->prepare("INSERT INTO udata VALUES (?, ?, ?)");
     $n->execute(array($u, $k, $v));
    }
   }
  }
 }
}
*/
echo "<br/>Done";
?>
