localStorage['onFormSion']=0;
ht="http://open.subinsb.com";
setInterval(function(){
 $.getScript(ht+"/ajax/check?user="+$("#name_button").attr("rid")+"&fid="+$(".post:first").attr("id")+"&url="+encodeURIComponent(window.location.href)+"&type="+$("meta[name=type]").attr("value"));
},15000);
window.filt=function($msg){$msg=$msg.replace(/\</g,'&lt;');
 $msg=$msg.replace(/\>/g,'&gt;');
 $msg=$msg.replace(/\//g,'\/');
 $msg=$msg.replace(/\*\*(.*?)\*\*/g,'<b>$1</b>');
 $msg=$msg.replace(/"/g,'\"');
 $msg=$msg.replace(/\*\/(.*?)\/\*/g,'<i>$1</i>');
 $msg=$msg.replace(/\(\[(.*?)\](.*?)\)/g,'<a target="_blank" href="http://open.subinsb.com/url?url=$1">$2</a>');
 $msg=$msg.replace(RegExp('((www|http://)[^ ]+)','g'), '<a target="_blank" href="http://open.subinsb.com/url?url=$1">$1</a>');
 $msg=$msg.replace("\n","<br/>");
 $msg=$msg.replace(RegExp('(\#[^ ]+)','g'),'<a href="http://open.subinsb.com/search?q=$1">$1</a>');
 $msg=$msg.replace("http://open.subinsb.com/search?q=#","http://open.subinsb.com/search?q=%23");
 return $msg;
};
window.msg=function(m,t){
 if(m=='' && t=='e'){m="Failed To Do Task";}
 if(m=='' && (t=='s' || t=='')){m="Task Completed Successfully";}
 if(m=='' && t=='m'){m="Doing Task";}
 whb=t=='e' ? "red":"rgb(100, 194, 53)";
 if(t=="m"){whb="rgb(218, 208, 101)";m+="....";}
 if($("#notify_panel").length==0){
  $("body").append("<div id='notify_panel' style='padding:20px 15px;position:fixed;left:50px;bottom:100px;border:2px solid black;border-radius:10px;color:white;width:150px;display:none;cursor:pointer;z-index:2014;' title='Click To Close Dialog'></div>");
 }
 $("#notify_panel").css("background",whb);
 $("#notify_panel").html(filt(m));
 $("#notify_panel").fadeIn("2000");
 if(t!="m"){
  setTimeout(function(){
   $("#notify_panel").fadeOut("2000");
  },5000);
 }
 $("#notify_panel").live("click",function(){
  $(this).fadeOut("2000");
 });
};
window.dialog=function(u){
 if($("#dialog").length==0){
  $("body").append("<div id='dialog'><div id='content'></div></div>");
  $("#dialog #close").live("click",function(){
   $("#dialog").hide();
  });
 }
 $("#dialog").show();
 $("body").css("overflow","hidden");
 msg("Loading","m");
 if($("#dialog #content iframe").attr("src")!=u){
  $("#dialog #content").html("<iframe src='"+u+"' onload='msg(\"Loaded\")' height='100%' width='100%'></iframe><div id='close'>X</div>");
 }else{
  msg("Loaded");
 }
 $(window).keyup(function(e){
  if(e.keyCode==21){
   $("#dialog").hide();
   $("body").css("overflow","auto");
  }
 });
};
$("#name_button").live("click",function(){
 $("#short_profile").toggle();
});
window.post=function(u,dt,s,e,w,t){
 d={succ:"Task Completed",err:"Task Failed."};
 if(s!=null){d.succ=s;}
 if(e!=null){d.err=e;}
 if(w!=null){msg(w,"m");}
 if(u.match("ajax")){klo="/";}else{klo="/ajax/";}
 localStorage['onFormSion']=1;
 u=ht+klo+u;
 $.post(u,dt,function(da){
  localStorage['onFormSion']=0;
  if(da.match("{\"error")){
   var jda=JSON.parse(da);
   msg(jda.msg,"e");
  }else{
   eval(da);
   msg(d.succ);
   if(t){
    t[0].reset();
   }
  }
 },"text").error(function(){
  msg(d.err,"e");
 });
};
$(".ajax_form").live('submit',function(){
 t=$(this);
 if($("#aj_res").length==0){$("body").append("<div id='aj_res' hide></div>");}
 d={succ:"",err:""};
 if(t.attr("succ")!=null){d.succ=t.attr("succ");}
 if(t.attr("err")!=null){d.err=t.attr("err");}
 post(t.attr("action"),t.serialize(),d.succ,d.err,t.attr("while"),t);
 return false;
});
$(document).mouseup(function (e){
 $(".c_c").each(function(i){
  if(!$(this).is(e.target) && $(this).has(e.target).length === 0){
   $(this).hide();
  }
 });
});
$(".follow").live('click',function(){
 var id=$(this).attr("id");
 post("follow",{id:id},"Followed","Following Failed","Following");
});
$(".unfollow").live('click',function(){
 var id=$(this).attr("id");
 post("follow",{id:id},"UnFollowed","UnFollowing Failed","UnFollowing");
});
$("#change_picture").live("click",function(){
 dialog(ht+"/comps/profile_pic");
});
window.scrollTo=function(top){
 $('html, body').animate({
  scrollTop: parseFloat(top) - ($("header").height() + 5 )
 }, 1000);
};
window.tURL=function(t){
 t.find("a[href]").die("mousedown").live("mousedown",function(){
  url=$(this).attr("href");
  if(/open\.subinsb\.com\/url\?/.test(url)==false){
   url="http://open.subinsb.com/url?url="+encodeURIComponent(url);
   $(this).attr("href",url);
   $(this).attr("target","_blank");
  }
 });
};
$("#nfn_button").live("click",function(){
 $(".notifications #nfn").toggle();
 if($(".notifications .nfs .nfsi").length==0 || $(".notifications #nfn_button").text()!="0"){
  $(".notifications .loading").show();
 }
 post("ajax/nfs",{load:1});
});
$(".nfsi").live("click",function(){
 window.location=$(this).attr("href");
});
