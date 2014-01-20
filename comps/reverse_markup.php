<?
function rch_url($m){
 $u=urldecode($m[0]);
 $t=$m[2]=='http://' ? $m[1]:$m[2];
 return $t."($u)";
}
function reverseMarkup($s){
 $s=preg_replace("/\*\*(.*?)\*\*/",'$1',$s);
 $s=preg_replace("/\*\/(.*?)\/\*/",'$1',$s);
 $s=preg_replace_callback("/\(\[(.*?)\](.*?)\)/","rch_url",$s);
 return $s;
}
?>
