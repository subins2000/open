<?
/* This file is ran hourly. Deletes old notification items that are older than 50 days */
include("../inc/config.php");
//include(getenv("OPENSHIFT_REPO_DIR")."php/inc/config.php");
$sql=$db->prepare("SELECT `posted` FROM `notify` ORDER BY `posted` ASC LIMIT 50");
$sql->execute(array());
while($r=$sql->fetch()){
 $time=$r['posted'];
 if(strtotime($time) < strtotime("-50 days")){
  $sql2=$db->prepare("DELETE FROM `notify` WHERE `posted`=?");
  $sql2->execute(array($time));
 }
}
?>