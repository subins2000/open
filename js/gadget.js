window.mcTop=function(){
 $('.msgs').animate({
  scrollTop: $(".msgs").height()
 },1000);
};
mcTop();
if($("input[name=to]").length!=0){
 to=$("input[name=to]").val();
 if(to!=""){
  setInterval(function(){
   if(localStorage['onFormSion']==0){
    $.getScript(ht+"/ajax/check_msg?to="+to+"&lid="+$("#"+to+".msgs .msg:last").attr("id"));
   }
  },10000);
 }
}
$(".msgEditor").smention(ht+"/ajax/get_users",{
 avatar:true,
 width:300
});
