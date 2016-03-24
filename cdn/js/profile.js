$(".tabs a").live("click",function(){
  v=$(this).text().toLowerCase();
  if(typeof magicWord=="undefined"){
    magicWord="/"+window.location.pathname.split("/")[1]+"/";
  }
  window.history.pushState("/", "/", magicWord+v);
  $(".tabs a").removeAttr("act");
  $(this).attr("act", 1);
  $(".noggler").removeAttr("show").attr("hide", 1);
  $(".noggler#"+v).removeAttr("hide").attr("show", 1);
});

window.edopened = 0;
$("#editBox").live("click",function(){
  if(edopened == 0){
    $("#about [editable]").each(function(){
      v = $(this).find("[data-value]").data("value");
      n = $(this).find("[data-label]").data("label");
      aap = $(this).data("textarea") == 1 ? '<textarea or="'+ v +'" name="'+ n +'" style="height: 85px;" id="editField" size="15" class="materialize-textarea">'+ v +'</textarea>' : '<input or="'+ v +'" name="'+ n +'" id="editField" type="text" size="15" value="'+ v +'" />';
      $(this).find("[data-value]").html(aap);
    });
    $("#editBox").text("Save Changes");
    edopened = 1;
  }else{
    (function($){$.fn.serializeAny=function(){var ret=[];$.each($(this).find(':input'),function(){ret.push(encodeURIComponent(this.name)+"="+encodeURIComponent($(this).val()));});return ret.join("&").replace(/%20/g, "+");}})(jQuery);
    open.post("saveProfile", $("#about").serializeAny(), {"success" : "Updated Profile", "error" : "Updation Failed", "loading" : "Updating...."});
    $("#about tr[editable]").each(function(){
      v=$(this).find("#editField");
      aap = format(v.val());
      v.html(aap);
    });
    $("#editBox").text("Edit Profile");
    edopened=0;
  }
});

$("#ch_hi").live("click",function(){
  open.dialog("http://open.sim/ajax/dialog/header");
});
