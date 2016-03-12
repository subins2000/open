<?php
/**
 * Get the no of Friends of friends which are not the current user
 */
$sql = $OP->dbh->prepare("SELECT COUNT(*) FROM `users` WHERE `id` NOT IN (SELECT `fid` FROM `conn` WHERE `uid` = :who)");
$sql->execute(array(":who" => $who));
$numUsers = $sql->fetchColumn() - 1;

$randomValue = rand(0, $numUsers);
$sql = $OP->dbh->prepare("SELECT `id` FROM `users` WHERE `id` NOT IN (SELECT `fid` FROM `conn` WHERE `uid`=:who) AND `id` != :who LIMIT ". $randomValue .", 5");
$sql->execute(array(":who" => $who));

if($sql->rowCount() != "0"){
   echo '<div class="suggestions" style="padding: 5px 0px;margin: 5px -10px 0px 0px;color: black;">';
     echo "<h4>Suggestions</h4>";
     while($r = $sql->fetch()){
        $userID = $r['id'];
        $name = get("name", $userID, false);
        $firstName = get("fname", $userID, false);
        $plink = get("plink", $userID);
        echo "<div class='sugg'>";
         echo "<div style='vertical-align:top;display:inline-block;'>";
            echo "<a href='$plink'>";
               echo "<img height='32' width='32' src='".get("avatar", $userID)."'/>";
            echo "</a>";
         echo "</div>";
         echo "<div style='vertical-align:top;display:inline-block;'>";
            echo "<a title='$name' href='$plink' style='padding-left:5px;'>$firstName</a><br/><cl/>";
            echo $OP->followButton($userID);
         echo "</div>";
        echo"</div>";
     }
     echo "<center><cl/><a href='find'>See More Suggestions</a></center>";
   echo'</div>';
   echo'<style>.sugg{margin:5px;padding-top:5px;}</style>';
}
