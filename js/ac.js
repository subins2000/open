/*
 * jQuery sMention plugin 0.1
 *
 * Copyright (c) 2014 Subin Siby
 *
 * licensed under the MIT license:
 * //www.opensource.org/licenses/mit-license.php
*/
(function($) {	
 $.fn.smention=function(url,options){
  if($(".sm_bar").length==0){
   $("body").append("<div class='sm_bar'><div class='sm_conts'></div><div class='sm_close'></div></div>");
  }
  localStorage['smlto']="";
  localStorage['smloc']="";
  lc=0;
  var down = [];
  return this.each(function() {
   $(this).on("keydown",function(e){
    if(e.keyCode==16 || e.keyCode==50){down[e.keyCode] = true;}
   });
   $(this).on("keyup",function(e){
    t=$(this);
    if(down[50] && down[16]){
     lc=t[0].selectionStart-1;
     t.data("callNow","enabled");
     t.enSmen(url,options);
    }
    localStorage['smlto']=t.val().substring(lc).split(/\(|\)|\*| /)[0];
    localStorage['smloc']=parseFloat(lc) + "," + (parseFloat(lc)+localStorage['smlto'].length);
    if((parseFloat(lc)+localStorage['smlto'].length) < t[0].selectionStart || localStorage['smlto']==""){
     t.unSmen();
    }
    if(e.keyCode==16 || e.keyCode==50){down[e.keyCode] = false;}
   });
  });
 };
 $.fn.enSmen=function(url,options){
  return this.each(function(){
   if($(this).data("squed")==undefined){
    $(this).data("squed",1);
    $(this).bind("keyup",function(e){
     t=$(this);
     smlto=localStorage['smlto'].replace("@","");
     if(typeof(options.extraParams)=="object"){
      data=$.extend({q:smlto},options.extraParams);
     }else{
      data={q:smlto};
     }
     if($(this).data("callNow")!="disabled" && e.keyCode!=16 && data.q!=""){
      $.post(url,data,function(obj){makeList(obj,options,t);},'JSON');
     }
    });
   }
  });
 };
 $.fn.unSmen=function(){
  return this.each(function(){
   $(this).data("callNow","disabled");
   $(".sm_bar").hide();
  });
 };
 function makeList(obj,options,t){
  qlist="";
  $.each(obj,function(i,elem){
   whb=i%2 == 0 ? "dark":"";
   qlist+="<div id='"+elem.id+"' class='sitem "+whb+"' title='"+elem.name+"'>";
    if(options.avatar==true){
     qlist+="<img class='avatar' height='24' width='24' src='"+elem.avatar+"'/>";
    }
    qlist+="<span class='name'>"+elem.name+"</span>";
   qlist+="</div>";
  });
  $(".sm_conts").html(qlist);
  disList(options,t);
 }
 function disList(options,t){
  if(options.width!=undefined){
   $(".sm_bar").width(options.width);
  }
  $(".sm_bar").data("from",t);
  $(".sm_bar").css("left",t.offset().left);
  $(".sm_bar").css("top",parseFloat(t.offset().top)-$(".sm_bar").height());
  $(".sm_bar").show();
 }
})(jQuery);
String.prototype.replaceBetween = function(start, end, what) {
 return this.substring(0, start) + what + this.substring(end);
};
$(document).ready(function(){
 $("body").on("click",".sm_bar .sitem",function(){
  var id=$(this).attr("id");
  parts=localStorage['smloc'].split(",");
  start=parseFloat(parts[0])+1;
  end=parts[1];
  t=$(".sm_bar").data("from");
  t.val(t.val().replaceBetween(start,end,id));
  $(".sm_bar").hide();
  t.focus();
 });
});
