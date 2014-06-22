<?
/* !! NOT A CRON JOB !!
 * This script was used ONCE when profile images location was changed.
 * This script removes all users' profile images
*/

include "inc/load.php";

$sql=$OP->dbh->prepare("SELECT * FROM users");
$sql->execute();
while($r = $sql->fetch()){
  	$OP->uid = $r['id'];
  	$OP->save("img", "");
}
?>