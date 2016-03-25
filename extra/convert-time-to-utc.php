<?php
require_once __DIR__ . "/load.php";

$table = "`chat`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['posted'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `posted` = ? WHERE `id` = ?");
  $nSQL->execute(array($new, $r['id']));
  $nSQL->closeCursor();
  echo $r['id'] . "<br/>\n";
}

$table = "`commentLikes`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['liked'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `liked` = ? WHERE `liked` = ?");
  $nSQL->execute(array($new, $r['liked']));
  $nSQL->closeCursor();
  echo $r['liked'] . "<br/>\n";
}

$table = "`comments`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['time'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `time` = ? WHERE `id` = ?");
  $nSQL->execute(array($new, $r['id']));
  $nSQL->closeCursor();
  echo $r['id'] . "<br/>\n";
}

$table = "`conn`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['since'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `since` = ? WHERE `since` = ?");
  $nSQL->execute(array($new, $r['since']));
  $nSQL->closeCursor();
  echo $r['since'] . "<br/>\n";
}

$table = "`likes`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['liked'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `liked` = ? WHERE `liked` = ?");
  $nSQL->execute(array($new, $r['liked']));
  $nSQL->closeCursor();
  echo $r['liked'] . "<br/>\n";
}

$table = "`notify`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['posted'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `posted` = ? WHERE `id` = ?");
  $nSQL->execute(array($new, $r['id']));
  $nSQL->closeCursor();
  echo $r['id'] . "<br/>\n";
}

$table = "`posts`";
$sql = $OP->dbh->prepare("SELECT * FROM $table");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $dt = new DateTime($r['time'], new DateTimeZone('EST'));
  $dt->setTimeZone(new DateTimeZone('UTC'));

  $new = $dt->format('Y-m-d H:i:s');

  $nSQL = $OP->dbh->prepare("UPDATE $table SET `time` = ? WHERE `id` = ?");
  $nSQL->execute(array($new, $r['id']));
  $nSQL->closeCursor();
  echo $r['id'] . "<br/>\n";
}

$sql = $OP->dbh->prepare("SELECT * FROM `users`");
$sql->execute();
foreach($sql->fetchAll() as $r){
  $udata = json_decode($r['udata'], true);
  if(isset($udata['joined'])){
    $dt = new DateTime($udata['joined'], new DateTimeZone('EST'));
    $dt->setTimeZone(new DateTimeZone('UTC'));
    $udata['joined'] = $dt->format('Y-m-d H:i:s');
  }
  $udata = json_encode($udata);
  $nSQL = $OP->dbh->prepare("UPDATE `users` SET `udata` = ? WHERE `id` = ?");
  $nSQL->execute(array($udata, $r['id']));
  $nSQL->closeCursor();
  echo $r['id'] . "<br/>\n";
}
