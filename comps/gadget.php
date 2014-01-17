<div class="chatgt">
 <div class="msggt">
  <div class="close">x</div>
  <?include("comps/chat_rend.php");?>
  <?echo show_chat("gadget");?>
 </div>
 <div class="usersgt">
  <?
   $sql=$db->prepare("SELECT fid FROM conn WHERE uid=:who AND fid IN (SELECT uid FROM conn WHERE fid=:who)");
   $sql->execute(array(":who"=>$who));
   while($r=$sql->fetch()){
    $id=$r['fid'];
    $fname=get("fname",$id,false);
    $name=get("name",$id,false);
    $img=get("avatar",$id);
    $st=get("status",$id);
    echo "<div class='user' id='$id'><img height='32' width='32' src='$img'/><span class='status $st'>$st</span><span class='name' title='$name'>$fname</span></div>";
   }
  ?>
 </div>
</div>
<script>$(".content").css("margin-right","350px");</script>
