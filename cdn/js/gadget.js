open.chat = {}; // Register `chat` object
open.chat.lastLoadedUser = 0; // The ID of the user whose messages was last loaded

/* When the chat sidebar is open, other elements shoul be positioned in a way that it wouldn't overlap the sidebar or the other way around : */
open.chat.alignOthers = function() {
  if($(window).width() > 720 && $("#users-nav").is(":visible") ){
    //$(".content").css("left", "-5%");
  }
};

if(localStorage['chatgtopen'] != 0) {
  open.chat.alignOthers();
}

/* Scroll the chat window to the last message (to the very bottom) */
open.chat.scrollToEnd = function(){
  if( $('.msgs').length != 0 ){
    $('.msgs').animate({
      scrollTop: $(".msgs")[0].scrollHeight
    }, 1000);
    open.externalLinks($(".msgs"));
  }
};
open.chat.scrollToEnd();

/* A function to check for new messages from server */
open.chat.check = function (type){
  to = $("input[name=to]").length != 0 ? $("input[name=to]").val():"gadget";
  if(type != "gadget"){
    setInterval(function(){
      if(localStorage['onFormSion'] == 0){
        open.checks.init({
          "fl"  : "mC",
          "mC"  : {
            "to"  : to,
            "lid" : $("#"+to+".msgs .msg:last").attr("id")
          }
        }, "yes"); // It's an interval
      }
    }, 15000);
  }
  if(type == "gadget"){
    open.checks.init({
      "fl"  : "mC",
      "mC"  : {
        "to"  : to,
        "all" : "true"
      }
    });
  }
};
$(".msgEditor").smention(open.host + "/ajax/getUsers",{
  avatar:true,
  width:300
}).live("keypress",function(e){
  if (e.keyCode == 13 && !e.shiftKey) {
    e.preventDefault();
    $(".chat_form").submit();
  }
});
open.chat.check(); // Check for new messages

/* Open the chat window of user */
$("#users-nav .user").live("click", function(){
  id = $(this)[0].id; // The requested user's ID
  $(".msggt").show();
  $(".msggt input[name=to]").val(id);
  $(".msggt #cwinopen").attr("href", "chat/"+id).text($(this).find(".name").attr("title"));
  $(".chatgt .msgs, .chat_form").attr("id", id);
  if(open.chat.lastLoadedUser != id){
    $("#"+id+".msgs").html("<h3>Initiating Chat...</h3>Every famous sites in the world was made by Nerds. A bot nerd is working for you right now to get the messages.");
    open.chat.check("gadget");
    $(".msggt .msgEditor").focus();
    open.chat.lastLoadedUser = id; // It was this guy's messages who was last loaded
  }
});

/* Close the user chatbox window */
$(".msggt .close").live("click", function(){
  $(this).parents(".msggt").hide();
});

/* Close the chat sidebar */
$(".chatgt #users-nav .cusgt .close").live("click", function(){
  $(".content").attr("style", "");
  $("#users-nav").hide();
  $(".openugt").show();
  $(".msggt").css("right", "30px");
  localStorage['chatgtopen'] = 0;
});

/* Open the chat sidebar when the "Open Chat" button is clicked */
$(".chatgt .openugt").live("click",function(){
  $("#users-nav").show();
  $(".openugt").hide();
  $(".msggt").css("right", "235px");
  localStorage['chatgtopen'] = 1; // Add info that the chat sidebar is open
  open.chat.alignOthers();
});

$(".chatgt .openugt").sideNav({
  edge: 'right',
  menuWidth: 200
});

/**
 * Display the "Open Chat" button if the sidebar is hidden
 */
if(localStorage['chatgtopen'] == 0){
  $("#users-nav").hide();
  $(".openugt").show();
}else{
  //$(".chatgt .openugt").sideNav("show");
}
