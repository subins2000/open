$(".navigation part").live("click",function(){
 v=$(this).text().toLowerCase();
 window.history.pushState("","","?part="+v);
 $(".navigation part").removeAttr("act");
 $(this).attr("act",1);
 $(".noggler").hide();
 $(".noggler#"+v).show();
});
edopened=0;
$("#editBox").live("click",function(){
 if(edopened==0){
  $("it[editable]").each(function(){
   v=$(this).find("v");
   n=$(this).find("n").text().toLowerCase().replace(/\s/,"");
   aap=$(this).attr("in")==1 ? '<textarea or="'+v.text()+'" name="'+n+'" style="height: 85px;" id="editField" size="15">'+v.text()+'</textarea>':'<input or="'+v.text()+'" name="'+n+'" id="editField" type="text" size="15" value="'+v.text()+'" />';
   v.replaceWith(aap);
  });
  $("#editBox").text("Save Changes");
  edopened=1;
 }else{
  (function($){$.fn.serializeAny=function(){var ret=[];$.each($(this).find(':input'),function(){ret.push(encodeURIComponent(this.name)+"="+encodeURIComponent($(this).val()));});return ret.join("&").replace(/%20/g, "+");}})(jQuery);
  post("update_profile",$("#about").serializeAny(),"Updated Profile","Updation Failed","Updating....");
  $("it[editable]").each(function(){
   v=$(this).find("#editField");
   aap='<v>'+filt(v.val())+'</v>';
   v.replaceWith(aap);
  });
  $("#editBox").text("Edit Profile");
  edopened=0;
 }
});
$("#ch_hi").live("click",function(){
 dialog("http://open.subinsb.com/ajax/dialog/header");
});
