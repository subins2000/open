<?
include("config.php");
ch();
if($_P){
 $av_names=array("gender"=>"gen","birthday"=>"birth","aboutme"=>"about","e-mail"=>"mail","phone"=>"phone","address"=>"add","livesat"=>"live","worksat"=>"work","loves"=>"lve","facebook"=>"fb","twitter"=>"tw","google+"=>"gplus","pinterest"=>"pin");
 foreach($_POST as $k=>$v){
  if(array_key_exists($k,$av_names) && $v!="Private" && $v!="DD/MM/YYYY"){
   if($k=="facebook" || $k=="twitter" || $k=="google+" || $k=="pinterest" && $v!=""){
    $v=str_replace("@","",$v);
    if($k=="facebook" && !preg_match("/\/\//",$v)){$v="http://www.facebook.com/$v";}
    if($k=="twitter" && !preg_match("/\/\//",$v)){$v="http://twitter.com/$v";}
    if($k=="google+" && !preg_match("/\/\//",$v)){$v="http://plus.google.com/$v";}
    if($k=="pinterest" && !preg_match("/\/\//",$v)){$v="http://www.pinterest.com/$v";}
    $v=preg_replace_callback('@((www|http://)[^ ]+)@',"ch_url",filt($v));
    $v=preg_replace_callback("/\>(.*?)\</",function($m){
     global$k;
     $m=$m[1];
     $m=explode("/",$m);
     $m=$m[3];
     $g=$m;
     if($k=="twitter"){$g="@$m";}
     $g=">$g<";
     return$g;
    },$v);
    save($av_names[$k],$v);
   }elseif($v!=""){
    save($av_names[$k],filt($v));
   }
  }
 }
}else{
 ser();
}
?>
