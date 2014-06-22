<?

$OP->init();
?>
<!DOCTYPE html>
<html><head>
 <?$t="Linked Acounts - Manage Account";$OP->inc("inc/head.php");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content">
  <h1>Linked Accounts</h1>
  You can manage the accounts you linked to <b>Open</b> here.<cl/>
  <?
  $sql=$OP->dbh->prepare("SELECT server FROM oauth_session WHERE user=?");
  $sql->execute(array($who));
  $fb=0;
  $tw=0;
  while($r=$sql->fetch()){
   if($r['server']=='Facebook' && $fb==0){
    $fb=1;
   }
   if($r['server']=='Twitter' && $tw==0){
    $tw=1;
   }
  }
  if($fb==1){
   echo"<b>Facebook</b> - Connected - <form method='POST' cp><button name='service' value='0' type='submit'>Remove Connection</button></form>";
  }else{
   echo"<b>Facebook</b> - Not Connected - <a href='" . HOST . "/oauth/login_with_facebook'><button>Add Connection</button></a>";
  }
  echo"<br/><cl/>";
  if($tw==1){
   echo"<b>Twitter</b> - Connected - <form method='POST' cp><button name='service' value='1' type='submit'>Remove Connection</button></form>";
  }else{
   echo"<b>Twitter</b> - Not Connected - <a href='" . HOST . "/oauth/login_with_twitter'><button>Add Connection</button></a>";
  }
  ?>
  <br/><cl/>Note that <b>Google+</b> sessions are not stored.
  <style>form[cp]{display:inline-block;}</style>
  <?
  $ser=isset($_POST['service']) ? $_POST['service']:"";
  if($ser!=""){
   if($ser=='0'){
    $sql=$OP->dbh->prepare("DELETE FROM oauth_session WHERE user=? AND server=?");
    $sql->execute(array($who, "Facebook"));
    $OP->sss("Successfully Removed", "Service 'Facebook' has been removed from your account. Too Add it back, reload page.");
   }elseif($ser=='1'){
    $sql=$OP->dbh->prepare("DELETE FROM oauth_session WHERE user=? AND server=?");
    $sql->execute(array($who, "Twitter"));
    $OP->sss("Successfully Removed", "Service 'Twitter' has been removed from your account. Too Add it back, reload page.");
   }else{
    $OP->ser("Error", "Service Not Found");
   }
  }
  ?>
 </div>
</body></html>
