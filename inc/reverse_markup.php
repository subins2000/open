<?
function reverseMarkup($s){
 $s=preg_replace("/\*\*(.*?)\*\*/",'$1', $s);
 $s=preg_replace("/\*\/(.*?)\/\*/",'$1', $s);
 return $s;
}
?>
