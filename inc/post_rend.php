<?
include("cmt_rend.php");
function show_posts($arr){
 global $who, $db, $OP;
 $h="";
 foreach($arr as $k=>$v){
  $id=$k;
  $prs=str_replace('pub','Public',str_replace('meo','Only Me',str_replace('fri','Friends',$v['prs'])));
  $prd=str_replace('pub','Everyone can see this post',str_replace('meo','Only You can see this post',str_replace('fri','Only your Friends can see this post',$v['prs'])));
  $lk=$OP->didLike($id, "post")===false ? "Like":"Unlike";
  $class=strtolower($lk)=="unlike" ? " unlike":"";
  $p=filt($v['p'], true);
  $plink=get("plink",$v['uid']);
  $h.="<div class='post blocks' id='$id'>";
   $h.="<div class='left block'>";
    $h.="<a href='$plink'><img src='".get("avatar",$v['uid'])."' class='pimg'/></a>";
   $h.="</div>";
   $h.="<div class='right block'>";
    $h.="<div class='top'>";
     $h.="<a href='$plink'>".get("name",$v['uid'],false)."</a>";
     $h.="<a class='time slink' href='http://open.subinsb.com/view/{$id}'>{$v['time']}</a>";
     $h.="<span class='slink' title='{$prd}'>{$prs}</span>";
     if($v['uid']==$who){
      $h.="<div class='author_box'><div class='author_panel c_c'><a class='de_post pointer' id='$id'>Delete Post</a><br/><cl/><a class='pointer' onclick='dialog(\"<textarea style=\\\"width: 50%;\\\">http://open.subinsb.com/view/{$id}</textarea>\", true)' id='$id'>Get Link</a></div></div>";
     }
    $h.="</div>";
    $h.="<div class='cont'>";
     $h.="$p";
    $h.="</div>";
    $h.="<div class='bottom'>";
     $h.="<div class='like_bar'><a class='pst like$class' id='$id'>$lk</a><span class='count lk' id='$id'>{$v['likes']}</span></div>";
     $h.="<div class='cmt_bar'><a class='pst cmt' id='$id'>Comment</a><span class='count ck' id='$id'>{$v['cmt']}</span></div>";
     $h.=show_cmt($id);
    $h.="</div>";
   $h.="</div>";
  $h.="</div>";
 }
 if(count($arr)==0){
  $h="<h2><center>No Posts Found</center></h2>";
 }
 return $h;
}
?>