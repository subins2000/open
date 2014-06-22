isFmCh=0;
window.mcTop=function(){
 if($('.msgs').length!=0){
  $('.msgs').animate({
   scrollTop: $(".msgs")[0].scrollHeight
  },1000);
 }
};
mcTop();
function doMchecks(isG){
 to=$("input[name=to]").length!=0 ? $("input[name=to]").val():"gadget";
 if(isG!="gadget"){
  setInterval(function(){
   if(localStorage['onFormSion']==0){
    sChecks.init({
     "fl"  : "mC",
     "mC"  : {
      "to"  : to,
      "lid" : $("#"+to+".msgs .msg:last").attr("id")
     }
    }, "yes"); // It's an interval
   }
  }, 7000);
 }
 if(isG=="gadget"){
  sChecks.init({
   "fl"  : "mC",
   "mC"  : {
    "to"  : to,
    "all" : "true"
   }
  });
 }
}
$(".msgEditor").smention(ht+"/ajax/get_users",{
 avatar:true,
 width:300
}).live("keypress",function(e){
 if (e.keyCode == 13 && !e.shiftKey) {
  e.preventDefault();
  $(".chat_form").submit();
 }
});
doMchecks();
$(".usersgt .user").live("click",function(){
 id=$(this)[0].id;
 $(".msggt").show();
 $(".msggt input[name=to]").val(id);
 $(".msggt #cwinopen").attr("href", "chat/"+id);
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
$(".cusgt .close").live("click",function(){
 $(".content").css("margin-right", "auto");
 $(".usersgt").hide();
 $(".openugt").show();
 $(".msggt").css("right", "30px");
 localStorage['chatgtopen']=0;
});
$(".chatgt .openugt").live("click",function(){
 if($(window).width() > 720){
  $(".content").css("margin-right", "350px");
 }
 $(".usersgt").show();
 $(".openugt").hide();
 $(".msggt").css("right", "235px");
 localStorage['chatgtopen']=1;
});
if(localStorage['chatgtopen']==0){
 $(".usersgt").hide();
 $(".openugt").show();
}
