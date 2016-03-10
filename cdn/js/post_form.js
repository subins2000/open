(function(n){n.fn.singleupload=function(t){var r=this,o=null,e=n.extend({action:'#',onSuccess:function(n,t){},onError:function(n){},OnProgress:function(n,t){var e=Math.round(n*100/t);r.html(e+'%')},name:'img'},t);n('#'+e.inputId).bind('change',function(){r.css('backgroundImage','none');var o=new FormData();o.append(n('#'+e.inputId).attr('name'),n('#'+e.inputId).get(0).files[0]);var t=new XMLHttpRequest();t.addEventListener('load',function(n){r.html('');var t=eval('('+n.target.responseText+')');if(t.code!=0){e.onError(t.code);return};var o=('<img src="'+t.url+'" style="width:'+r.width()+'px;height:'+r.height()+'px;"/>');r.append(o);e.onSuccess(t.url,t.data)},!1);t.upload.addEventListener('progress',function(n){e.OnProgress(n.loaded,n.total)},!1);t.open('POST',e.action,!0);t.send(o)});return this}}(jQuery));
/* End jQuery.form */

var prntf = ".post_form "; // The Post Form
$(prntf +"#show_form").on('click',function(){
  t=$(this);
  $(prntf+"#post_full_form").show();
  t.hide();
  $(prntf+".textEditor").focus();
});
$(prntf +"#prtoggle").live("click",function(){
  $('#privacy').show();
  $('#privacy')[0].size=2;
});
$(prntf +'#privacy').live("click",function(){
  $('#privacy').hide();
});
$(prntf +"#ptwit").live("click",function(){
  if($(this).find("#twverify").val()==''){
    window.location="http://open.sim/oauth/login_with_twitter";
  }else{
    $(this).find('input[type=checkbox]').click();
  }
});
$(prntf +"#pfbit").live("click",function(){
  if($(this).find("#fbverify").val()==''){
    window.location="http://open.sim/oauth/login_with_facebook";
  }else{
    $(this).find('input[type=checkbox]').click();
  }
});
$(".short_news .close").live("click",function(){
  id = $(this).parents(".short_news").attr("id");
  localStorage["shnews"] = id;
  $(".short_news").hide();
});
if( typeof localStorage["shnews"] != "undefined" ){
  id = $(".short_news").attr("id");
  if( localStorage["shnews"] != id && $(".short_news").is(":hidden") ){
    $(".short_news").show();
  }else{
    $(".short_news").hide();
  }
}else{
  $(".short_news").show();
}
$(prntf +".close").live("click",function(){
  $(prntf+"#post_full_form").hide();
  $(prntf+"#show_form").show();
});
$(prntf +".cam").live("click",function(){
  $(prntf +"#upload").click();
});
$(prntf +"#upload").live("change", function(){
  if( $(this).val() != ""){
    $(prntf +".cam").addClass("b-red");
  }else{
    $(prntf +".cam").removeClass("b-red");
  }
});
$(prntf +".form").live("submit", function(e){
  e.preventDefault();
  var theForm = $(this);
  open.notify("Posting", "m");
  var fd = new FormData(theForm[0]);
  var xhr = new XMLHttpRequest();  
  xhr.open("POST", open.host + "/ajax/post");
  xhr.addEventListener("load", function(ev) {
      var response = ev.target.responseText;
      if( response.match("{\"error") ){
        var json = JSON.parse(response);
        open.notify(json.msg, "e");
      }else{
        $("<script>"+ response +"</script>").appendTo("body").remove();
        open.notify("Posted");
        $(prntf +".cam").removeClass("b-red");
        theForm[0].reset();
      }
  });
  xhr.send(fd);
});