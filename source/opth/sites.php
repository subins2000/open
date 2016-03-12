<?php
\Fr\LS::init();
require_once "$docRoot/inc/class.opth.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <?php $OP->head("Opth Sites");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <h1>Opth Sites</h1>
        <?php
        if(isset($_POST['registerSite'])){
          $title = $_POST['title'];
          $url = $_POST['url'];
          $description = $_POST['description'];
          $redirect_url = $_POST['redirect_url'];
          
          $register = Opth::register($title, $url, $description, $redirect_url);

          if($register === true){
            $OP->sss("Registered", "The site is registered.");
          }else if($register == "exists"){
            $OP->ser("Registered Already", "The site is registered already.", "html", false);
          }else{
            $OP->ser("Problems", "Something occured, please try again or contact support team.", "html", false);
          }
        }
        ?>
        <p>Sites you registered with Opth :</p>
        <?php
        $sql = $OP->dbh->prepare("SELECT * FROM `opth_sites` WHERE `uid` = ?");
        $sql->execute(array($who));
        if($sql->rowCount() == 0){
          echo "You haven't registered any sites with Opth.";
        }else{
          while($r = $sql->fetch()){
        ?>
            <table>
              <thead title="<?php echo $r['description'];?>">
                <tr>
                  <td><a href="<?php echo O_URL . "/opth/sites/edit?id={$r['id']}";?>"><?php echo $r['title'];?></a></td>
                  <td><?php echo $r['url'];?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>API Key</td>
                  <td><?php echo $r['api_key'];?></td>
                </tr>
                <tr>
                  <td>API Secret</td>
                  <td><?php echo $r['api_secret'];?></td>
                </tr>
              </tbody>
            </table>
        <?php
          }
        }
        ?>
        <h4>New Site</h4>
        <p>Register your site for implementing Opth.</p>
        <form method="POST" style="margin-left: 10px;">
          <label>
            <span>Site Name</span>
            <input clear type="text" name="title" />
          </label>
          <label>
            <span>URL</span>
            <input clear type="text" name="url" placeholder="http://example.com" />
          </label>
          <label>
            <span>Oneline Description</span>
            <input clear type="text" name="description" placeholder="A one line description not exceeding 50 characters." />
          </label>
          <label>
            <span>Valid Redirect URLs</span>
            <textarea rows="5" name="redirect_url" placeholder="Type URLs in each line"></textarea>
          </label>
          <p>By registering, the site/you/administrator/owner are agreeing to <a target="_blank" href="<?php echo O_URL ;?>/open.pdf">Open's T&C as well as Opth's T&C </a></p>
          <button name="registerSite" style="margin: 10px;font-size: 15px;">Register Site</button>
        </form>
        <style>
        .content form label{
          display:block;
          margin-top: 15px;
        }
        .content form input, form textarea{
          width: 97%;
          margin-top: 10px;
        }
        </style>
      </div>
    </div>
  </body>
</html>
