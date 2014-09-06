<?php
require_once "$docRoot/inc/render.php";
$id = $OP->format($_POST['id']);
if($id != ""){
 $_POST['all']=1;
 $ht=$OP->rendFilt(Render::comment($id));
?>
localStorage['fKey']=$("#<?php echo$id?>.cmt_form .textEditor").val();$("#<?php echo$id;?>.comments").replaceWith("<?php echo$ht;?>");$("#<?php echo$id;?>.comments").show();$("#<?php echo$id;?>.ck.count").text($("#<?php echo$id;?>.comments").find(".comment").length);$("#<?php echo$id;?>.comments .cmt_form").find("#clod").val("mom");$("#<?php echo$id?>.cmt_form .textEditor").val(localStorage['fKey']);
<?php
}
?>