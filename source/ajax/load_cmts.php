<?
$OP->inc("inc/cmt_rend.php");
$id=$OP->format($_POST['id']);
if($id!=""){
 $_POST['all']=1;
 $ht=$OP->rendFilt(show_cmt($id));
?>
localStorage['fKey']=$("#<?echo$id?>.cmt_form .textEditor").val();$("#<?echo$id;?>.comments").replaceWith("<?echo$ht;?>");$("#<?echo$id;?>.comments").show();$("#<?echo$id;?>.ck.count").text($("#<?echo$id;?>.comments").find(".comment").length);$("#<?echo$id;?>.comments .cmt_form").find("#clod").val("mom");$("#<?echo$id?>.cmt_form .textEditor").val(localStorage['fKey']);
<?
}
?>
