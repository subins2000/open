<?php
\Fr\LS::init();
$_GET['q'] = isset($_GET['q']) ? Open::encodeQuery($_GET['q'], true) : "";
$searchQuery = $OP->format( $_GET['q'] );
if($searchQuery != ''){
  $sql = $OP->dbh->prepare("UPDATE `trend` SET `hits` = `hits` + 1 WHERE `title` = ?");
  $sql->execute(array($_GET['q']));
  if($sql->rowCount() == 0){
    $sql = $OP->dbh->prepare("INSERT INTO `trend` (`title`, `hits`) VALUES(?, '1')");
    $sql->execute(array($_GET['q']));
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="type" value="search"></meta>
    <?php $OP->head("Search", "ac,post_form,home,time,gadget", "ac,home,post_form,gadget");?>
  </head>
  <body>
    <?php include "$docRoot/inc/header.php";?>
    <div class="wrapper">
      <div class="content">
        <form action="<?php echo Open::URL('search');?>" method="GET" style="margin: 20px 0;" class="row">
          <input type="text" class="col s10" value="<?php echo $searchQuery;?>" name="q" id="q" autocomplete="off" />
          <button class="btn col s2 orange"><i class="material-icons">search</i></button>
        </form>
        <?php
        include "$docRoot/inc/post_form.php";
        ?>
        <div class="feed">
          <?php
          include "$docRoot/inc/feed.php";
          ?>
        </div>
      </div>
    </div>
    <?php include "$docRoot/inc/gadget.php";?>
  </body>
</html>
