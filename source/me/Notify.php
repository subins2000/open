<?

$OP->init();
?>
<!DOCTYPE html>
<html><head>
 <?$t="Linked Acounts - Manage Account";$OP->inc("inc/head.php");?>
</head><body>
 <?
 $OP->inc("inc/header.php");
 if(isset($_POST['submit'])){
  $shouldNotSend=array();
  $optSend=array("cmt", "fol", "msg", "men");
  foreach($optSend as $v){
   if(!isset($_POST[$v])){
    $shouldNotSend[$v]="";
   }
  }
  $OP->save("NfS", $shouldNotSend);
 }
 $nfs=!isset($shouldNotSend) ? get("NfS", $who):$shouldNotSend;
 function checkOrNot($t){
  global $nfs;
  echo "name='$t'";
  if(!isset($nfs[$t])){
   echo "checked='checked'";
  }
 }
 ?>
 <div class="content">
  <h1>Notification Settings</h1>
  What kind of notifications should we send you by E-Mail ?<cl/>
  <form method="POST">
   <table cellspacing="15">
    <tbody>
     <tr>
      <td>Type Of Notification</td><td>Should We Send It ?</td>
     </tr>
     <tr>
      <td>When Someone Comments On Your Post</td><td><input type="checkbox" <?checkOrNot("cmt");?>/></td>
     </tr>
     <tr>
      <td>When Someone Follows You</td><td><input type="checkbox" <?checkOrNot("fol");?>/></td>
     </tr>
     <tr>
      <td>When Someone Messages You</td><td><input type="checkbox" <?checkOrNot("msg");?>/></td>
     </tr>
     <tr>
      <td>When Someone Mentions You in Posts/Comments</td><td><input type="checkbox" <?checkOrNot("men");?>/></td>
     </tr>
    </tbody>
   </table>
   <input name='submit' type="submit" value="Update Settings"/>
  </form>
 </div>
</body></html>
