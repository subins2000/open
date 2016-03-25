<?php
require_once __DIR__ . "/../load.php";

$sql = $OP->dbh->prepare("SELECT * FROM `users`");
$sql->execute();
while($r = $sql->fetch()){
  $udata = json_decode(htmlspecialchars_decode($r['udata']), true);
  if(isset($udata['birth'])){
    $date = str_replace('/', '-', $udata['birth']);
    $udata['birth'] = date('Y-m-d', strtotime($date));
  }
  $udata = json_encode($udata);
  $nSQL = $OP->dbh->prepare("UPDATE `users` SET `udata` = ? WHERE `id` = ?");
  $nSQL->execute(array($udata, $r['id']));
  echo $r['id'] . "<br/>\n";
}
