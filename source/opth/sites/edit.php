<?php
\Fr\LS::init();
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
        if(isset($_POST['updateSite'])){
          $title = $_POST['title'];
          $url = $_POST['url'];
          $description = $_POST['description'];
          $redirect_url = $_POST['redirect_url'];
          
          $sql = $OP->dbh->prepare("SELECT COUNT(`id`) FROM `opth_sites` WHERE `id` = ?");
          $sql->execute(array($_GET['id']));
          
          if($sql->fetchColumn() == 0){
            $OP->ser("Doesn't Exist", "The site doesn't exist in Opth");
          }else{
            $sql = $OP->dbh->prepare("UPDATE `opth_sites` SET `title` = ?, `url` = ?, `description` = ?, `redirect_url` = ?");
            $sql->execute(array($title, $url, $description, $redirect_url));

            $OP->sss("Updated", "The site was updated successfully.");
          }
        }
        $sql = $OP->dbh->prepare("SELECT * FROM `opth_sites` WHERE `uid` = ? AND `id` = ?");
        $sql->execute(array($who, $_GET['id']));
        if($sql->rowCount() == 0){
          echo "The site doesn't exist.";
        }else{
          while($r = $sql->fetch()){
            $name = $r['title'];
            $url = $r['url'];
            $description = $r['description'];
            $redirect_url = $r['redirect_url'];
          }
        }
        ?>
        <form method="POST" style="margin-left: 10px;" action="<?php echo \Fr\LS::curPageURL();?>">
          <label>
            <span>Site Name</span>
            <input type="text" name="title" value="<?php echo $name;?>" />
          </label>
          <label>
            <span>URL</span>
            <input type="text" name="url" value="<?php echo $url;?>" placeholder="http://example.com" />
          </label>
          <label>
            <span>Oneline Description</span>
            <input type="text" name="description" value="<?php echo $description;?>"
            placeholder="A one line description not exceeding 50 characters." />
          </label>
          <label>
            <span>Valid Redirect URLs</span>
            <textarea rows="5" name="redirect_url" placeholder="Type URLs in each line"
            ><?php echo $redirect_url;?></textarea>
          </label>
          <button name="updateSite" style="margin: 10px;font-size: 15px;">Update Site</button>
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
