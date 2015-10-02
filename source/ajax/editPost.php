<?php
\Fr\LS::init();
require_once "$docRoot/inc/render.php";

if( $_P && isset($_POST['id']) && isset($_POST['post']) && isset($_POST['privacy']) ){
  $id = $_POST['id'];
  $post = $_POST['post'];
  $privacy = $_POST['privacy'];
  if( !is_numeric($id) || ($privacy != "pub" && $privacy != "fri" && $privacy != "meo") ){
    $OP->ser();
  }
  if( $post == "" ){
    $OP->ser("Post is Blank", "The post content was left blank. It is not allowed.", "json");
  }
  $sql = $OP->dbh->prepare("UPDATE `posts` SET `post` = ?, `privacy` = ?, `time` = NOW() WHERE `id` = ? AND `uid` = ?");
  $sql->execute(array(
    $post,
    $privacy,
    $id,
    curUser
  ));
  if( $sql->rowCount() == 0 ){
    $OP->ser("No changes was made", "Either you didn't make any changes or you're no the owner of this post");
  }else{
    $sql = $OP->dbh->prepare("SELECT * FROM `posts` WHERE `id` = ?");
    $sql->execute(array($id));
    $postsArr = $sql->fetchAll(PDO::FETCH_ASSOC);
    $html = Render::post($postsArr);
    echo $html;
  }
}elseif( isset($_POST['id']) ){
  $sql = $OP->dbh->prepare("SELECT `post`, `privacy` FROM `posts` WHERE `id` = ? AND `uid` = ?");
  $sql->execute(array(
    $_POST['id'],
    curUser
  ));
  if( $sql->rowCount() == 0 ){
    $OP->ser();
  }
  $data = $sql->fetch(PDO::FETCH_ASSOC);
  $post = $data['post'];
  $pvc = $data['privacy'];
  $arr = array();
  $arr['textarea'] = "<textarea style='width: 100%;height:100px;'>". $OP->format($post) ."</textarea>";
  $arr['privacy'] = '<select name="privacy">
      <option value="pub"'. ( ($pvc == "pub") ? "selected='selected'" : "" ) .'>Public</option>
      <option value="fri"'. ( ($pvc == "fri") ? "selected='selected'" : "" ) .'>Friends</option>
      <option value="meo"'. ( ($pvc == "meo") ? "selected='selected'" : "" ) .'>Only Me</option>
    </select>';
  echo json_encode($arr);
}else{
  $OP->ser();
}
?>