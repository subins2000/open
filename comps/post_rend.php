<?
include("cmt_rend.php");
function show_posts($arr){
 global$who, $db;
 $sql=$db->prepare("SELECT pid FROM likes WHERE uid=?");
 $sql->execute(array($who));
 $pk=array();
 while($r=$sql->fetch()){$pk[]=$r['pid'];}
 $h="";
 $co="red";
 $lcor=0;
 foreach($arr as $k=>$v){
  $id=$k;
  $prs=str_replace('pub','Public',str_replace('meo','Only Me',str_replace('fri','Friends',$v['prs'])));
  $prd=str_replace('pub','Everyone can see this post',str_replace('meo','Only You can see this post',str_replace('fri','Only your Friends can see this post',$v['prs'])));
  $lk=array_search($id,$pk)===false ? "Like":"Unlike";
  $cor=array("yell","blu","or","gree");
  $lcor++;
  $lcor=$lcor==4 ? 0:$lcor;
  $de=$cor[$lcor];
  $co=$v['uid']==$who ? "":$de;
  $p=$v['p'];
  $plink=get("plink",$v['uid']);
  $h.="<div class='post $co' id='$id'>";
   $h.="<div class='left'>";
    $h.="<a href='$plink'><img src='".get("img",$v['uid'])."' class='pimg'/></a>";
   $h.="</div>";
   $h.="<div class='right'>";
    $h.="<div class='top'>";
     $h.="<a href='$plink'>".get("name",$v['uid'],false)."</a>";
     $h.="<a class='time slink' href='//open.subinsb.com/view?id={$id}'>{$v['time']}</a>";
     $h.="<span class='slink' title='{$prd}'>{$prs}</span>";
     if($v['uid']==$who){
      $h.="<div class='author_box'><div class='author_panel c_c'><button class='de_post' id='$id'>Delete Post</button></div></div>";
     }
    $h.="</div>";
    $h.="<div class='cont'>";
     $h.="$p";
    $h.="</div>";
    $h.="<div class='bottom'>";
     $h.="<div class='like_bar'><a class='pst like' id='$id'>$lk</a><span><span> -> </span></span><span class='count lk' id='$id'>{$v['likes']}</span></div>";
     $h.="<div class='cmt_bar'><a class='cmt' id='$id'>Comment</a><span><span> -> </span></span><span class='count ck' id='$id'>{$v['cmt']}</span></div>";
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
