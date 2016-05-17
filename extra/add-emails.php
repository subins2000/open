<?php
require_once __DIR__ . "/../load.php";

$sql = $OP->dbh->prepare("SELECT * FROM `users`");
$sql->execute();
while($r = $sql->fetch()){
  $nSQL = $OP->dbh->prepare("UPDATE `users` SET `email` = ? WHERE `id` = ?");
  $nSQL->execute(array($r['username'], $r['id']));
  echo $r['id'] . "<br/>\n";
}
