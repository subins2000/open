<?$OP->init();?>
<!DOCTYPE html>
<html><head>
 <?$OP->head("Invite", "time,ac,gadget", "ac,gadget");?>
</head><body>
 <?$OP->inc("inc/header.php");?>
 <div class="content">
  <h1>Invite</h1>
  Invite your friends to Join Open.
  <?
  if(isset($_GET['gmail'])){
   if($_GET['gmail']=="success"){
    $OP->sss("Invited GMail Contacts", "The Invitations to your GMail Contacts have been added to the Mail Server. It will be processed soon. Thank You.<br/><cl/>");
   }
  }
  ?>
  <script src="http://connect.facebook.net/en_US/all.js"></script>
  <script>
  FB.init({
   appId:'private_value',
   cookie:true,
   status:true,
   xfbml:true,
   frictionlessRequests : true
  });
  function FBInvite(){
   FB.ui({
    method: 'apprequests',
    message: 'Invite your FB friends to Join Open : http://open.subinsb.com '
   },function(response) {
    if (response) {
      dialog('<h2>Successfully Invited</h2><p>Thank you very very much.</p>', true);
    } else {
      dialog('<h2>Error</h2><p>Failed To Invite Persons</p>', true);
    }
   });
  }
  </script><br/>
  <center>
   <a href="#" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background: #3b579d url(<?echo HOST;?>/source/cdn/img/fb_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;margin-right:5px;" onclick="FBInvite()">Invite Facebook Friends</a>
   <br/><cl/>
   <a href="oauth/invite_gmail" style="display: inline-block;height: 43px;margin: 0px;padding: 0px 20px 0px 52px;font-family: 'Ubuntu', sans-serif;font-size: 18px;font-weight: 400;color: #fff;line-height: 41px;background:#4F9FCA url(<?echo HOST;?>/source/cdn/img/g+_icon.png) no-repeat 14px 8px scroll;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;text-decoration: none;cursor:pointer;">Invite GMail Contacts</a>
  </center>
 </div>
 <?$OP->inc("inc/gadget.php");?>
</body></html>
