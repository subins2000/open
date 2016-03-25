<?php
\Fr\LS::init();

if($_P){
  $av_names=array("gender"=>"gen", "birthday"=>"birth", "aboutme"=>"about", "e-mail"=>"mail", "phone"=>"phone", "address"=>"add", "livesin"=>"live", "worksat"=>"work", "loves"=>"lve", "facebook"=>"fb", "twitter"=>"tw", "google+"=>"gplus", "pinterest"=>"pin");
  foreach($_POST as $k => $v){
    if(array_key_exists($k, $av_names) && $v != "Private" && $v != "DD/MM/YYYY"){
      if(($k == "facebook" || $k == "twitter" || $k == "google+" || $k == "pinterest") && $v != ""){
        $v = str_replace("@", "", $v);
        if($k == "facebook" && preg_match("/\/\//", $v)){
          /**
           * Extract username from URL
           */
          $urlParts = @parse_url($v);
          if($urlParts['host'] != "facebook.com" || $urlParts['host'] != "fb.com"){
            continue;
          }
          $v = $urlParts['path'];
        }else if($k === "twitter" && preg_match("/\/\//", $v)){
          $v = "http://twitter.com/$v";
        }else if($k === "twitter" && substr($v, 0, 1) == "@"){
          $v = substr_replace($v, "", 0, 1);
        }else if($k === "google+" && preg_match("/\/\//", $v)){
          $v = "http://plus.google.com/$v";
        }else if($k === "pinterest" && preg_match("/\/\//", $v)){
          $v = "http://www.pinterest.com/$v";
        }
        $OP->save($av_names[$k], $v);
      }else if($v != ""){
        $OP->save($av_names[$k], $OP->format($v));
      }
    }
  }
}else{
  $OP->ser();
}
?>
