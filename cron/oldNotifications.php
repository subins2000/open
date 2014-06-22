<?
/* This file is ran hourly. Deletes old notification items that are older than 50 days */
include("../inc/load.php");
//include(getenv("OPENSHIFT_REPO_DIR")."php/inc/load.php");

$sql=$OP->dbh->prepare("SELECT `posted` FROM `notify` ORDER BY `posted` ASC LIMIT 50");
$sql->execute(array());

while($r=$sql->fetch()){
 $time = $r['posted'];
 if(strtotime($time) < strtotime("-50 days")){
  		$sql2 = $OP->dbh->prepare("DELETE FROM `notify` WHERE `posted`=?");
  		$sql2->execute(array($time));
 }
}
?>