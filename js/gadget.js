isFmCh=0;
window.mcTop=function(){
 $('.msgs').animate({
  scrollTop: parseFloat($(".msgs").height()) + $(".msgs").innerHeight()
 },1000);
};
mcTop();
function doMchecks(isG){
 if($("input[name=to]").length!=0){
  to=$("input[name=to]").val();
  if(to!="" && isG!="gadget"){
   setInterval(function(){
    if(localStorage['onFormSion']==0){
     $.getScript(ht+"/ajax/check_msg?to="+to+"&lid="+$("#"+to+".msgs .msg:last").attr("id"));
    }
   },10000);
  }
 }
 if(isG=="gadget"){
  $.getScript(ht+"/ajax/check_msg?to="+to+"&all=true");
 }
}
$(".msgEditor").smention(ht+"/ajax/get_users",{
 avatar:true,
 width:300
});
doMchecks();
$(".usersgt .user").live("click",function(){
 id=$(this)[0].id;
 $(".msggt").show();
 $("input[name=to]").val(id);
 $(".chatgt .msgs, .chat_form").attr("id",id);
 if(isFmCh!=id){
  $("#"+id+".msgs").html("<h3>Initiating Chat...</h3>Every famous sites in the world was made by Nerds. A bot nerd is working for you right now to get the messages.");
  doMchecks("gadget");
  isFmCh=id;
 }
});
$(".msggt .close").live("click",function(){
 $(".msggt").hide();
});
