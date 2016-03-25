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
  <div class="short_news" id="2016-03-25 05:49" hide>
    <h4>New</h4>
    <p>New UI for Open</p>
    <p style="border-top:1px solid black;margin-top:5px;">Current Version : <b>0.6</b></p>
    <div class="close"><i class="material-icons">close</i></div>
  </div>
  <div class="post_form blocks" id="post_form">
    <form action="ajax/post" method="POST" class="form">
      <span id="ajaxResponse" hide></span>
      <div>
        <input type="text" id="show_form" placeholder="Have Something To Share ?">
        <div id="post_full_form" hide>
          <div class="close" title="Close"><i class="material-icons tiny">close</i></div>
          <textarea placeholder="Share What's New :-)" class="textEditor materialize-textarea" name="post"><?php if($_SERVER['REDIRECT_PAGE']=="/search" && $searchQuery!=""){echo "$searchQuery ";}?></textarea>
          <div class="blocks row">
            <select id="privacy" name="privacy" class="c_c" hide>
              <option value='pub'>Public</option>
              <option value='fri'>Friends</option>
              <option value='meo'>Only Me</option>
            </select>
            <ul id='privacy_dropdown' class='dropdown-content'>
              <li><a data-privacy="pub" class="orange">Public</a></li>
              <li><a data-privacy="fri">Friends</a></li>
              <li class="divider"></li>
              <li><a data-privacy="meo">Only Me</a></li>
            </ul>
            <input type="file" id="upload" name="upload" hide />
            <a class="block col m1 dropdown-button" id="prtoggle" data-activates='privacy_dropdown'>
              <i class="material-icons small">people</i>
            </a>
            <a class="block cam col m1"><i class="material-icons small">add_a_photo</i></a>            
            <a title="Post To Facebook" class="block col m2" id="pfbit">
              <input type="hidden" id="fbverify" value="<?php echo $fbv;?>"/>
              <input <?php echo $fb;?> name="facebook" type="checkbox" id="post_to_fb" />
              <label class="block" for="post_to_fb">Facebook</label>
            </a>
            <a title="Post To Twitter" class="block col m6" id="ptwit">
              <input type="hidden" id="twverify" value="<?php echo $twv;?>"/>
              <input <?php echo $tw;?> name="twitter" type="checkbox" id="post_to_tw" />
              <label class="block" for="post_to_tw">Twitter</label>
            </a>
            <button class="btn green s2 right">Post</button>
          </div>
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
