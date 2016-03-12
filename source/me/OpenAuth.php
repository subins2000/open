<?php
\Fr\LS::init();
require_once "$docRoot/inc/class.opth.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Opth");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <h1>Linked Accounts</h1>
        <p>You can manage the accounts you linked to <b>Open</b> here.</p><cl/>
        <?php
        $sql = $OP->dbh->prepare("SELECT `server` FROM `oauth_session` WHERE `user` = ?");
        $sql->execute(array($who));
        $fb = 0;
        $tw = 0;
        while($r=$sql->fetch()){
          if($r['server'] == 'Facebook' && $fb == 0){
            $fb = 1;
          }
           if($r['server'] == 'Twitter' && $tw == 0){
            $tw = 1;
          }
        }
        if($fb == 1){
          echo"<b>Facebook</b> - Connected - <form method='POST' cp><button name='service' value='0' type='submit'>Remove Connection</button></form>";
        }else{
          echo "<b>Facebook</b> - Not Connected - <a href='" . O_URL . "/oauth/login_with_facebook'><button>Add Connection</button></a>";
        }
        echo "<br/><cl/>";
        if($tw==1){
          echo "<b>Twitter</b> - Connected - <form method='POST' cp><button name='service' value='1' type='submit'>Remove Connection</button></form>";
        }else{
          echo "<b>Twitter</b> - Not Connected - <a href='" . O_URL . "/oauth/login_with_twitter'><button>Add Connection</button></a>";
        }
        ?>
        <cl/>
        <p>Note that <b>Google+</b> sessions are not stored.</p>
        <?php
        $service = isset($_POST['service']) ? $_POST['service']:"";
        if($service != ""){
          if($service == '0'){
            $sql = $OP->dbh->prepare("DELETE FROM `oauth_session` WHERE `user` = ? AND `server` = ?");
            $sql->execute(array($who, "Facebook"));
            $OP->sss("Successfully Removed", "Service 'Facebook' has been removed from your account. To Add it back, reload page.");
          }elseif($service == '1'){
            $sql = $OP->dbh->prepare("DELETE FROM `oauth_session` WHERE `user` = ? AND `server` = ?");
            $sql->execute(array($who, "Twitter"));
            $OP->sss("Successfully Removed", "Service 'Twitter' has been removed from your account. To Add it back, reload page.");
          }else{
            $OP->ser("Error", "Service Not Found");
          }
        }
        ?>
        <h1>Open Auth</h1>
        <?php
        if(isset($_POST['action']) && isset($_POST['id']) && $_POST['action'] == "revoke"){
          $sql = $OP->dbh->prepare("SELECT COUNT(1) FROM `opth_session` WHERE `sid` = ?");
          $sql->execute(array($_POST['id']));
          
          if($sql->fetchColumn() != "0"){
            $sql = $OP->dbh->prepare("DELETE FROM `opth_session` WHERE `sid` = ?");
            $sql->execute(array($_POST['id']));
            
            $OP->sss("Revoked", "The site you requested was revoked from accessing your Opth account.");
          }
        }
        ?>
        <p>Open Auth is a a technology to login to other sites with your Open Account. With Open Auth (Opth), you can :</p>
        <ul>
          <li>Login Anonymously</li>
          <li>Login to other sites without revealing your email</li>
          <li>Choose what the site can send you</li>
          <li>Choose what you want to share with the site</li>
        </ul>
        <p>We act as an intermediary between the site and you. So, the site won't know you and it will understand you only by a specially designed ID. This ID will be different for every sites you login to.</p>
        <p>You can configure what the site sees and can do with your account.</p>
        <p>Here is a list of apps you have authorized your account to.</p>
        <?php
        $sql = $OP->dbh->prepare("SELECT * FROM `opth_session` WHERE `uid` = ?");
        $sql->execute(array($who));
        $sites = $sql->fetchAll(PDO::FETCH_ASSOC);
        
        if(count($sites) == 0){
          $OP->sss("No Sites", "You haven't authorized any site through Opth.");
        }else{
          echo "<table>
            <thead>
              <tr>
                <td>Site</td>
                <td>Permissions</td>
                <td>Authorized</td>
                <td>Expires</td>
                <td></td>
              </tr>
            </thead>
            <tbody>";
          foreach($sites as $site){
            $site_info = $OP->dbh->prepare("SELECT `title` FROM `opth_sites` WHERE `id` = ?");
            $site_info->execute(array($site['sid']));
            $site_info = $site_info->fetch(PDO::FETCH_ASSOC);
            
            echo "<tr>";
              echo "<td>{$site_info['title']}</td>";
              echo "<td>";
                foreach(unserialize($site['permissions']) as $perm){
                  echo "<li>" . Opth::readable_scope($perm) . "</li>";
                }
              echo "</td>";
              echo "<td>". date("F j, Y", $site['created']) ."</td>";
              echo "<td>". date("F j, Y", $site['expiry']) ."</td>";
              echo "<td><form method='POST'><input type='hidden' name='action' value='revoke' /><input type='hidden' name='id' value='{$site['sid']}' /><button class='btn red'>Revoke Access</button></form></td>";
            echo "</tr>";
          }
          echo "</tbody></table>";
        }
        ?>
        <p style="color: red;">Note that after you revoke access, all data on the site being revoked will be lost.</p>
        <h1>Opth Dev</h1>
        <p>Would you like to implement Opth into your site ?</p>
        <p>See <a href="<?php echo O_URL . "/opth/sites"?>">Opth Sites</a> for more information.</p>
      </div>
    </div>
  </body>
</html>
