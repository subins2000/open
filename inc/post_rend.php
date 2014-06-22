<?
require_once "$docRoot/inc/cmt_rend.php";
function show_posts($postArr){
 global $who, $OP;
 $html = "";
 if(count($postArr) == 0){
  	$html="<h2><center>No Posts Found</center></h2>";
 }else{
 	/* $v contains information about the post*/
 	foreach($postArr as $v){
  
  		/* The user ID of the post owner */
  		$owner = $v['uid'];
  		/* The Post ID */
  		$id 	 = $v['id'];
  		
  		$privacyShort = str_replace('pub','Public',
   		str_replace('meo','Only Me',
     			str_replace('fri','Friends', 
     				$v['privacy']
     			)
   		)
  		);
  		$privacyDescription = str_replace('pub','Everyone can see this post', 
  			str_replace('meo','Only You can see this post',
  				str_replace('fri','Only your Friends can see this post', 
  					$v['privacy']
  				)
  			)
  		);
  
  		$liked = $OP->didLike($id, "post")===false ? "Like":"Unlike";
  		$class = strtolower($liked)=="unlike" ? " unlike":"";
  
  		/* We format the post from @1 to @Subin Siby */
  		$post	 = $OP->format($v['post'], true);
  		/* The Profile Link */
  		$plink = get("plink", $owner);
  
  		$html .= "<div class='post blocks' id='$id'>";
   		$html .= "<div class='left block'>";
    			$html .= "<a href='$plink'><img src='".get("avatar", $owner)."' class='pimg'/></a>";
   		$html .= "</div>";
   		$html .= "<div class='right block'>";
    			$html .= "<div class='top'>";
     				$html .= "<a href='$plink'>".get("name", $owner, false)."</a>";
     				$html .= "<a class='time slink' href='" . HOST . "/view/{$id}'>{$v['time']}</a>";
     				$html .= "<span class='slink' title='{$privacyDescription}'>{$privacyShort}</span>";
     				if($owner == $who){
      				$html .= "<div class='author_box'><div class='author_panel c_c'><a class='de_post pointer' id='$id'>Delete Post</a><br/><cl/><a class='pointer' onclick='dialog(\"<h2>Post Link</h2><textarea style=\\\"width: 50%;\\\">" . HOST . "/view/{$id}</textarea>\", true)' id='$id'>Get Link</a></div></div>";
     				}
    			$html .= "</div>";
    			$html .= "<div class='cont'>";
     				$html .= $post;
    			$html .= "</div>";
    			$html .= "<div class='bottom'>";
     				$html .= "<div class='like_bar'><a class='pst like$class' id='$id'>$liked</a><span class='count lk' id='$id'>{$v['likes']}</span></div>";
     				$html .= "<div class='cmt_bar'><a class='pst cmt' id='$id'>Comment</a><span class='count ck' id='$id'>{$v['comments']}</span></div>";
     				$html .= show_cmt($id);
    			$html .= "</div>";
   		$html .= "</div>";
  		$html .= "</div>";
 	}
 }
 return $html;
}
?>