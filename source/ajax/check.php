<?php
Header("content-type: application/x-javascript");
if(isset($_POST['u']) && $_POST['u'] != $who && $_POST['u'] != "undefined"){
 echo "window.location.reload();"; /* Reload The Page */
 exit;
}
if(loggedIn && $_P){
 if(isset($_POST['p']) && isset($_POST['pt']) && $_POST['p']!="undefined" && $_POST['pt']!="view"){
  /* Are There New Posts ? */
  require_once "$docRoot/inc/render.php";
  $id = $_POST['p'];
  $url = urldecode($_POST['cu']);
  $url = parse_url($url);
  $path = explode("/", $url['path']);
  if($path[0] == "/search"){
    preg_match("/\/search\/(.*?)/", $matches);
     $q = urldecode($matches[0]);
     $sql = $OP->dbh->prepare("SELECT * FROM `posts` WHERE `id` > :lid AND `post` LIKE :q AND (
       `uid`=:who OR `uid` IN (
         SELECT `fid` FROM `conn` WHERE `uid`=:who
       ) AND (
         `privacy`='pub' OR (
           `privacy`='fri' AND `uid` IN (
             SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (
               SELECT `uid` FROM `conn` WHERE `fid`=:who
             )
           )
         )
       )
     ) ORDER BY `id` DESC LIMIT 10");
     $sql->execute(array(
        ":q" => "%$q%",
        ":who" => $who,
        ":lid" => $id
     ));
  }elseif($_POST['pt']=="profile"){
     $pU = $path[1];
     $sql = $OP->dbh->prepare("SELECT * FROM `posts` WHERE `id` > :lid AND `uid`=:fid ORDER BY `id` DESC LIMIT 10");
     $sql->execute(array(
        ":fid" => $pU,
        ":lid" => $id
     ));
  }else{
     $sql=$OP->dbh->prepare("SELECT * FROM posts WHERE `id` > :lid AND (
       `uid`=:who OR `uid` IN (
         SELECT `fid` FROM `conn` WHERE `uid`=:who
       ) AND (
         `privacy`='pub' OR (
           `privacy`='fri' AND `uid` IN (
             SELECT `fid` FROM `conn` WHERE `uid`=:who AND `fid` IN (
               SELECT `uid` FROM `conn` WHERE `fid`=:who
             )
           )
         )
       )
     ) ORDER BY `id` DESC LIMIT 10");
     $sql->execute(array(
        ":who" => $who,
        ":lid" => $id
     ));
  }
  if($sql->rowCount()!=0){
     $postArr = $sql->fetchAll(PDO::FETCH_ASSOC);
     $html = $OP->rendFilt(Render::post($postArr));
     /* Give a fadein effect on new posts */
     $effect = "";
     foreach($postArr as $id => $v){
        $effect .= "$('#" . $id . ".post').hide().fadeIn(2000);";
     }
     $k = array_keys($postArr);
?>
   if($(".post:first").attr("id") != "<?php echo $k[0];?>"){
      p="<?php echo $html;?>";$(".post:first").before(p);
      <?php echo $effect;?>
   }
<?php
  }
 }
 /* Are There New Notifications ?*/
 $sql = $OP->dbh->prepare("SELECT `red` FROM `notify` WHERE `red`='0' AND `uid`=?");
 $sql->execute(array($who));
 $count = $sql->rowCount();
 if($count != 0){
?>
  $(".notifications #nfn_button").text("<?php echo$count;?>");$(".notifications #nfn_button").addClass("b-red");
<?php 
 }
 if(isset($_POST['fl'])){
  $requestedFile=$_POST['fl'];
  $_POST=$_POST[$_POST['fl']];
  if($requestedFile=="mC"){
   include "$docRoot/source/ajax/checkMsg.php";
  }
 }
}else{
 header("Content-type: text/html");
 $OP->ser();
}
?>
