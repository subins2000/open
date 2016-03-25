<?php
/**
 * Get the no of Friends of friends which are not the current user
 */
$sql = $GLOBALS['OP']->dbh->prepare("SELECT COUNT(*) FROM `users` WHERE `id` NOT IN (SELECT `fid` FROM `conn` WHERE `uid` = :who)");
$sql->execute(array(":who" => curUser));
$numUsers = $sql->fetchColumn() - 1;

$randomValue = rand(0, $numUsers);
$sql = $GLOBALS['OP']->dbh->prepare("SELECT `id` FROM `users` WHERE `id` NOT IN (SELECT `fid` FROM `conn` WHERE `uid`=:who) AND `id` != :who LIMIT ". $randomValue .", 4");
$sql->execute(array(":who" => curUser));

$suggestHTML = "";
if($sql->rowCount() != "0"){
  $suggestHTML .= '<div class="suggestions row" style="padding: 5px 0px;margin: 5px -10px 0px 0px;color: black;">';
    while($r = $sql->fetch()){
      $userID = $r['id'];
      $name = get("name", $userID, false);
      $firstName = get("fname", $userID, false);
      $plink = get("plink", $userID);
      $suggestHTML .= "<div class='sugg col s3'>";
        $suggestHTML .= "<div class='left'>";
          $suggestHTML .= "<a href='$plink'>";
             $suggestHTML .= "<img height='32' width='32' src='".get("avatar", $userID)."'/>";
          $suggestHTML .= "</a>";
        $suggestHTML .= "</div>";
        $suggestHTML .= "<div class='left'>";
          $suggestHTML .= "<a title='$name' href='$plink' class='name'>$firstName</a><cl/>";
          $suggestHTML .= $GLOBALS['OP']->followButton($userID);
        $suggestHTML .= "</div>";
      $suggestHTML .= "</div>";
    }
  $suggestHTML .= "<center><cl/><a href='find' class='btn blue moreSuggestions'>See More Suggestions</a></center>";
  $suggestHTML .= '</div>';
}
return $suggestHTML;
