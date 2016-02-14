<?php
if($_SERVER['PHP_SELF'] == "/search.php"){
  $q = $OP->format($_GET['q']);
}
$tw = "";
$fb = "";
$twv = "";
$fbv = "";
$sql = $OP->dbh->prepare("SELECT `server`, `access_token` FROM `oauth_session` WHERE `user` = ?");
$sql->execute(array($who));
while($r = $sql->fetch()){
  if($r['server'] == 'Twitter'){
    $tw = $r['access_token'] == '' ? "":"checked";
    $twv = $r['access_token'] == '' ? "":"true";
  }elseif($r['server']=='Facebook'){
    $fb = $r['access_token'] == '' ? "":"checked";
    $fbv = $r['access_token'] == '' ? "":"true";
  }
}
if( loggedIn ){
?>
  <div class="short_news" id="2014-01-28 01:05" hide>
    <h2>New</h2>
    <p>Invite your friends to join Open via the <a href="invite">Invite Page</a>.</p>
    <p style="border-top:1px solid black;margin-top:5px;">Current Version : <b>0.5</b></p>
    <div class="close">x</div>
  </div>
  <div class="post_form blocks" id="post_form">
    <form action="ajax/post" method="POST" class="form">
      <span id="ajaxResponse" hide></span>
      <div>
        <input type="text" id="show_form" placeholder="Have Something To Share ?">
        <div id="post_full_form" hide>
          <div class="close" title="Close">x</div>
          <textarea placeholder="Share What's New :-)" class="textEditor" name="post"><?php if($_SERVER['REDIRECT_PAGE']=="/search" && $searchQuery!=""){echo "$searchQuery ";}?></textarea>
          <div style="display: inline;position: relative;">
            <a class="button" id="prtoggle"></a>
            <select id="privacy" name="privacy" class="c_c" hide>
              <option value='pub'>Public</option>
              <option value='fri'>Friends</option>
              <option value='meo'>Only Me</option>
            </select>
            <a class="button cam"></a>
            <input type="file" id="upload" name="upload" hide />
            <a title="Post To Facebook" class="button small" id="pfbit">
              <input type="hidden" id="fbverify" value="<?php echo $fbv;?>"/>
              <input <?php echo$fb;?> onclick="$(this).click();" name="facebook" type="checkbox"/>
              <span class="block">Facebook</span>
            </a>
            <a title="Post To Twitter" class="button small" id="ptwit">
              <input type="hidden" id="twverify" value="<?php echo $twv;?>"/>
              <input <?php echo$tw;?> onclick="$(this).click();" name="twitter" type="checkbox"/>
              <span class="block">Twitter</span>
            </a>
          </div>
          <input type="submit" class="b-green" style="float:right;min-height: 30px;min-width: 100px;font-size: 14px;" value="Post"/>
        </div>
      </div>
    </form>
  </div>
<?php
}
if($_SERVER['PHP_SELF'] == "/search.php"){
  echo "<div style='background:#FAB359;color:white;padding:10px 15px;border-radius:10px;margin-bottom: 5px;'>";
    if($q != ""){
      echo "Showing posts that match \"<b>$q</b>\"";
    }else{
      echo "Showing All Public Posts";
    }
  echo "</div>";
}
?>