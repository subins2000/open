localStorage['requested'] = 0;
open.cache.comments = {};

$(".suggestions .follow").die("click").live("click",function(event){
  $(this).parents(".sugg").remove();
  $.get(open.host + "/inc/suggest", function(d){
    $(".suggestions").replaceWith(d);
  });
});
/* Post Based Functions */
$(".post .cont .readMore").live("click", function(){
  $(this).replaceWith($(this).find("div").html());
});

/* Like/Unlike a post */
$(".post .pst.like").live("click",function(){
  likeBTN = $(this); // The like button clicked
  pid = likeBTN.attr("id"); // The post ID
  count = $(".post#"+ pid).find(".pst.lk.count"); // The elements containing like count
  newText = likeBTN.text() == "Like" ? "Unlike" : "Like"; // The new text - Like becomes Unlike & Unlike becomes Like

  newCNT = likeBTN.text() == "Unlike" ? parseFloat( count.text() ) - 1 : parseFloat( count.text() ) + 1; // Change like count if Like - +1, Unlike - -1
  count.text(newCNT);
  likeBTN.text(newText);
  
  likeBTN.removeClass("unlike").addClass( newText.toLowerCase() );
  $.post(open.host + "/ajax/like", {"id": pid}).error(function(){
    likeBTN.text(newText);
    likeBTN.click();
  });
});
$(".post .author_box").live("click",function(){
  $(this).find(".author_panel").toggle();
});

/* Edit a post */
$(".post .editPost").live("click", function(){
  var pID = $(this).parents(".post").attr("id");
  var thePost = $( ".post#"+ pID );
  open.post("ajax/editPost", {"id" : pID}, {
    "success"   : "Loaded",
    "error"    : "Failed Loading",
    "loading"  : "Loading"
  }, undefined, function(data){
    var data = JSON.parse(data);
    var submitButton = "<cl/><a class='b-green button updatePost'>Update Post</a>";
    thePost.find(".right .cont").html(data.textarea + submitButton);
    thePost.find(".right .top .privacy").html(data.privacy);
  });
});

/* Update a post */
$(".post .updatePost").live("click", function(){
  var pID = $(this).parents(".post").attr("id");
  var thePost = $( ".post#"+ pID );
  var post = thePost.find(".right .cont textarea").val();
  var privacy = thePost.find(".right .top .privacy select").val();
  open.post("ajax/editPost", {
    "id"     : pID,
    "post"    : post,
    "privacy"  : privacy
  }, {
    "success"   : "Updated",
    "error"    : "Updating Failed",
    "loading"  : "Updating"
  }, undefined, function(data){
    $( ".post#"+ pID ).fadeOut(500, function(){
      thePost.replaceWith(data);
      $( ".post#"+ pID ).hide().fadeIn(500);
    });
  });
});

/* Delete a post */
$(".post .deletePost").live("click",function(){
  id = $(this).parents(".post").attr("id");
  cfm = confirm("Are you really sure that you want to delete this post ?");
  if(cfm == true){
    open.post("deletePost", {"id": id}, {"success" : "Deleted Post", "error" : "Failed To Delete Post", "loading" : "Deleting"});
  }
});

/* Get the hyperlink of the post */
$(".post .author_box .postLink").live("click", function(){
  pID = $(this).parents(".post").attr("id")
  pLink = open.host + "/view/" + pID;
  var html = "<h4>Post Link</h4>";
  html += "<textarea style='width: 50%;'>"+ pLink +"</textarea>";
  html += "<p>You can pass along the above URL to view your post from anywhere on the web</p>";
  open.dialog(html, true);
});

/* On post image click */
$(".post .cont .postImage").live("click", function(){
  URL = $(this).data("fullsize");
  open.dialog("<center title='Click to view image full size in a new tab'><a href='"+ URL +"' target='_blank'><img src='"+ URL +"' /></a></center>", false);
});

$(".post .cmt.like").live("click",function(){
  t=$(this);
  id=t.attr("id");
  co=$(".comments").find("#"+id+".lk.count");
  k=t.text()=="Like" ? "Unlike":"Like";
  t.text(k);
  n=t.text()=="Like" ? parseFloat(co.text())-1:parseFloat(co.text())+1;
  co.text(n);
  t.removeClass("unlike");
  t.addClass(k.toLowerCase());
  $.post(open.host + "/ajax/cmtLike", {id:id}).error(function(){
    t.text(k);
    t.click();
  });
});
$(".post .pst.cmt").live("click",function(){
  id=$(this).attr("id");
  c=$("#"+id+".comments");
  c.toggle();
  c.find(".textEditor").click();
});
$(".comment .author_cmt_box").live("click",function(){
  $(this).find(".author_cmt_panel").toggle();
});
$(".de_cmt").live("click",function(){
  id = $(this).attr("id");
  open.post("deleteCmt", {id:id}, {"success" : "Deleted Comment", "error" : "Failed To Delete Comment", "loading" : "Deleting"});
});
$(".reply_cmt").live("click",function(){
  id = $(this).attr("id");
  $("#"+id+".cmt_form").find(".textEditor").focus().val("@"+$(this).attr("data-user")+" ");
  open.scrollTo($("#"+id+".cmt_form").offset().top);
});
$(".load_more_comments").live("click",function(){
  var ID = $(this).attr("id");
  var data = {"id" : ID};
  if( typeof open.cache.comments[ID] == "undefined"){
    open.post('loadCmt', data, {"success" : "Loaded Comments", "error" : "Loading Comments Failed", "loading" : "Loading"});
    open.cache.comments[ID] = 1; // Tell that a comment request started
  }
});
function load_more_posts(){
  var ID = $(".post:last").attr("id");
  var mVal = $("meta[name=type]").attr("value");
  t = $(".load_more_posts");
  t.find(".normal").hide();
  t.find(".loader").show();
  if(mVal == "view"){
    return false;
  }else{
    if(mVal == "profile"){
      var aer = $("meta[name=oid]").attr("value");
      var data = {uid:aer,id:ID};
      if($(".noggler#feed").is(":hidden")){
        return false;
      }
    }else if(mVal=="search"){
      var query = window.location.href.split('q=')[1];
      var splW = window.location.pathname.split("/");
      if(typeof query == "undefined"){
        query = "";
      }else if(query.match("&")){
        query=query.split('&')[0];
      }
      if(splW.length>2){
        query = window.location.pathname.split("/")[2];
      }
      var data={"id": ID, "q": query};
    }else{
      var data={"id": ID};
    }
  }
  if(localStorage['requested']==0){
    open.post('loadPost', data, {"success" : "Loaded Posts", "error" : "Loading Posts failed", "loading" : "Loading"});
    localStorage['requested'] = 1;
  }
}
$(window).scroll(function(){
  if($(window).scrollTop() + $(window).height() == $(document).height() && $(".post").length != 0){
    load_more_posts();
  }
});
$(".load_more_posts .normal").live("click",function(){
  load_more_posts();
});
$(".textEditor").smention(open.host + "/ajax/getUsers", {
  avatar: true,
  width: 300,
  position: "below",
  cache: true
}).live("keyup", function(){
  if($(this).data("realInnerH") == null){
    $(this).data("realInnerH", $(this).innerHeight());
  }
  var scrollHeight = $(this)[0].scrollHeight < $(this).data("realInnerH") ? $(this).data("realInnerH") : $(this)[0].scrollHeight;
  $(this).innerHeight(scrollHeight);
});
open.externalLinks($(".post .cont"));
open.externalLinks($(".comment .cont"));