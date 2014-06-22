prntf=".post_form ";
$(prntf+"#show_form").on('click',function(){
 t=$(this);
 $(prntf+"#post_full_form").show();
 t.hide();
 $(prntf+".textEditor").focus();
});
$(prntf+"#prtoggle").live("click",function(){
 $('#privacy').show();
 $('#privacy')[0].size=2;
});
$('#privacy').live("click",function(){
 $('#privacy').hide();
});
$(prntf+"#ptwit").live("click",function(){
 if($(this).find("#twverify").val()==''){
  window.location="http://open.subinsb.com/oauth/login_with_twitter";
 }else{
  $(this).find('input[type=checkbox]').click();
 }
});
$(prntf+"#pfbit").live("click",function(){
 if($(this).find("#fbverify").val()==''){
  window.location="http://open.subinsb.com/oauth/login_with_facebook";
 }else{
  $(this).find('input[type=checkbox]').click();
 }
});
$(".short_news .close").live("click",function(){
 id=$(this).parents(".short_news").attr("id");
 localStorage["shnews"]=id;
 $(".short_news").hide();
});
if(typeof localStorage["shnews"] != "undefined"){
 id=$(".short_news").attr("id");
 if(localStorage["shnews"]!=id && $(".short_news").is(":hidden")){
  $(".short_news").show();
 }else{
  $(".short_news").hide();
 }
}else{
 $(".short_news").show();
}
$(prntf+".close").live("click",function(){
 $(prntf+"#post_full_form").hide();
 $(prntf+"#show_form").show();
 //$(prntf+"").
});
