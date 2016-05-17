window.open = {
  "host"   : "http://open.sim",
  "cache" : {}
};
if (open.host != "http://op"+"en.sim" && window.location.protocol != "https:"){
  window.location.href = "https:" + window.location.href.substring(window.location.protocol.length);
}
localStorage['onFormSion'] = 0;

window.clog = function(e){ // For Debugging
  return console.log(e);
};

/* Routine checks via AJAX to do various things like :
 * 1. Load new posts
 * 2. Log out the user when he logs out from another page
 * 3. Updates online statuses of friends in the chat sidebar
 * 4. Other stuff
*/
open.checks = {
  "init" : function(extra, isInterval){
    var data = {
      "u"   : $("#name_button").attr("who"), // current user ID
      "p"   : $(".post:first").attr("id"), // The first post ID
      "cu"  : encodeURIComponent(window.location.href), // The current page URL
      "pt"  : $("meta[name=type]").attr("value") // The page type (eg: profile, home)
    };
    
    /* Start checking in an interval when it hasn't already started */
    if(typeof isInterval == "undefined" && typeof open.checks.intervalLoader == "undefined"){
      open.checks.intervalLoader = open.checks.normalCheck();
    }else if(typeof isInterval != "undefined" && isInterval != "normal"){
      window.clearInterval(open.checks.intervalLoader);
    }
    
    var wholeJSON = $.extend(open.checks.removeUD(extra), open.checks.removeUD(data));
    var intSt = open.checks.interval(wholeJSON, function(){});
  },
  
  /* The function that runs periodically by the interval */
  "interval" : function(wholeJSON, callback){
    $.post(open.host + "/ajax/check", wholeJSON, function(){ 
      /* Callback when the request was successful */
      callback("success");
    }, "script").error(function(){
      /* Callback when the request wasn't successful */
      callback("failed");
    });
  },
  
  /* Normal interval to do AJAX requests */
  "normalCheck" : function(){
    return setInterval(function(){
      open.checks.init({}, "normal");
    }, 30000);
  },
  
  /* Remove undefined values from the array recursively */
  "removeUD" : function(wholeJSON){
    if(typeof wholeJSON == "object" && wholeJSON.length != 0){
      var newJSON = wholeJSON;
      $.each(newJSON, function(i, elem){
        if(typeof newJSON[i] == "undefined"){
          newJSON[i] = "undefined";
        }else if(typeof newJSON[i] == "object"){
          newJSON[i] = open.checks.removeUD(newJSON[i]);
        }
      });
      return newJSON;
    }
  }
}

/* Only do checks if user is logged in */
if(typeof $("#name_button").attr("who") != "undefined"){
  open.checks.init();
}

window.filter = function($msg){
  $msg = $msg.replace(/\</g,'&lt;');
  $msg =$msg.replace(/\>/g,'&gt;');
  $msg = $msg.replace(/\//g,'\/');
  // if($f===true){
  // $msg=$msg.replace(/\*\*(.*?)\*\*/g,'<b>$1</b>');
  // $msg=$msg.replace(/"/g,'\"');
  // $msg=$msg.replace(/\*\/(.*?)\/\*/g,'<i>$1</i>');
  // $msg=$msg.replace(RegExp('((www|http://)[^ ]+)','g'), '<a target="_blank" href="http://open.sim/url?url=$1">$1</a>');
  // $msg=$msg.replace("\n", "<br/>");
  // $msg=$msg.replace(RegExp('(\#[^ ]+)','g'),'<a href="http://open.sim/search?q=$1">$1</a>');
  // $msg=$msg.replace("http://open.sim/search?q=#", "http://open.sim/search?q=%23");
  // }
  return $msg;
};

/* To notify the user. The thing to say in the `message` var and the type of message in `type` :
 * s OR ''   - success message
 * e     - error message
 * m    - loading message
*/
open.notify = function(message, type){
  if(message == '' && type == 'e'){
    message = "Failed To Do Task";
  }
  if(message == '' && (type == 's' || type == '')){
    message = "Task Completed Successfully";
  }
  if(message == '' && type == 'm'){
    message = "Doing Task";
  }
  background = type == 'e' ? "red" : "rgb(100, 194, 53)";
  if(type == "m"){
    background = "rgb(218, 208, 101)";
    message += "....";
  }
  if($("#notify_panel").length == 0){
    $("body").append("<div id='notify_panel' style='padding:5px 20px;position:fixed;left:50px;bottom:100px;box-shadow: 6px 6px 5px 0px rgba(0, 0, 0, .25);border-radius:5px;color:white;display:none;cursor:pointer;z-index:2014;font-size: 14px;max-width:150px;' title='Click To Close Message'></div>");
  }
  $("#notify_panel").css("background", background);
  $("#notify_panel").html(filter(message));
  $("#notify_panel").fadeIn("2000");
  if(type != "m"){
    setTimeout(function(){
      $("#notify_panel").fadeOut("2000");
    }, 5000);
  }
  $("#notify_panel").live("click", function(){
    $(this).fadeOut("2000");
  });
};

/**
 * Show dialog boxes. If a URL is going to be passed to show an iframe, don't set `notFrame` to boolean true
 */
open.dialog = function(content, notFrame){
  if( content == "close" ){
    $("#dialog #content #close").click(); // Close Dialog box
  }else{
    if($("#dialog").length == 0){
      $("body").append("<div id='dialog'><div id='content'></div></div>");
      $("#dialog #close").live("click",function(){
        $("#dialog").hide();
      });
      $("#dialog").live("click", function(e){
        if(!$("#dialog #content").is(e.target) && !$("#dialog #content").has(e.target)){
          $("#dialog").hide();
        }
      });
      $(window).live("keyup",function(e){
        if(e.keyCode == 27){
          $("#dialog").hide();
        }
      });
    }
    $(".content").trigger("mouseup");// Close all dialogs with .c_c
    $("#dialog").show();
    $("#dialog #content").css("overflow", "hidden");
    if(typeof notFrame == "undefined"){
      open.notify("Loading", "m");
      if( $("#dialog #content iframe").attr("src") != content ){
        $("#dialog #content").html("<iframe src='"+ content +"' onload='window.open.notify(\"Loaded\");' height='100%' width='100%'></iframe><div id='close'>X</div>");
      }else{
        open.notify("Loaded");
      }
    }else{
      $("#dialog #content").css("overflow", "auto");
      $("#dialog #content").html("<div style='margin:5px 5px 0px;'>"+ content +"</div><div id='close'>X</div>");
    }
  }
};

/* Popup the small box when the name button is clicked */
$("#name_button").live("click",function(){
  $("#short_profile").toggle();
});

/* url       - The URL to send the request to
 * data      - The Data to send in JSON format
 * success   - The Success message that should be shown after request was successful
 * error  - The Error message that should be shown if request wasn't successful
 * onLoad    - The message while the request is being sent
 * form      - The jQuery form selector
*/

open.post = function(url, data, messages, form, callback){
  /* The default messages */
  var messages = $.extend({
    success : "Task Completed",
    error   : "Task Failed",
    loading : "Loading"
  }, messages);
  open.notify(messages.loading, "m");
  
  /* Is there /ajax/ already in the URL given */
  if( url.match("ajax") ){
    klo = "/";
  }else{
    klo = "/ajax/";
  }
 
  localStorage['onFormSion'] = 1;
  url = open.host + klo + url;
 
  $.post(url, data, function(response){
    localStorage['onFormSion'] = 0;
    if(response.match("{\"error")){
      var json = JSON.parse(response);
      open.notify(json.msg, "e");
    }else{
      if( typeof callback == "function" ){
        callback(response);
      }else{
        eval(response);
      }
      if(form){
        form[0].reset();
      }
      open.notify(messages.success);
    }
  }, "text").error(function(){
    open.notify(messages.error, "e");
  });
};
$(".ajax_form").live('submit', function(e){
  e.preventDefault();
  var theForm = $(this);
  if($("#aj_res").length==0){
    $("body").append("<div id='aj_res' hide></div>");
  }
  open.post(theForm.attr("action"), theForm.serialize(), {
    "success"   : theForm.attr("success"),
    "error"   : theForm.attr("error"),
    "loading"   : theForm.attr("while")
  }, theForm);
});

$(document).mouseup(function (e){
  $(".c_c").each(function(i){
    if(!$(this).is(e.target) && $(this).has(e.target).length === 0){
      $(this).hide();
    }
  });
});

/* Follow a person */
$(".follow").live('click',function(){
  var id = $(this).attr("id");
  open.post("follow", {"id": id}, {"success" : "Followed", "error" : "Following Failed", "loading" : "Following"});
});

/* Unfollow a person */
$(".unfollow").live('click',function(){
  var id = $(this).attr("id");
  open.post("follow", {"id": id}, {"success" : "UnFollowed", "error" : "UnFollowing Failed", "loading" : "UnFollowing"});
});

/* Show the chnage picture dialog box */
$("#change_picture").live("click",function(){
  open.dialog(open.host + "/ajax/dialog/profilePicture");
});

/* Using animation, scroll to a top offest */
open.scrollTo = function(top){
  $('html, body').animate({
    scrollTop: parseFloat(top) - ($("header").height() + 5 )
  }, 1000);
};

/* Make external links open in a new tab */
open.externalLinks = function(elem){
  elem.find("a[href]").die("mousedown").live("mousedown",function(){
    url = $(this).attr("href");
    if(/open\.subinsb\.com/.test(url) === false){
      newURL = "http://open.sim/url/" + url;
      $(this).attr("href", newURL);
      $(this).attr("target", "_blank");
    }
  });
};
$("#nfn_button").live("click",function(){
  if($(".notifications .nfs .nfsi").length == 0 || $(".notifications #nfn_button").text() != "0"){
    $(".notifications .loading").show();
  }
  if($(".notifications #nfn").is(":hidden")){
    open.post("ajax/nfs", { "load" : 1 });
    $(".notifications #nfn").show();
  }else{
    $(".notifications #nfn").hide();
  }
});

$('[data-activates=slide-out]').sideNav({
  edge: 'left',
  menuWidth: 200, // Default is 24
});
