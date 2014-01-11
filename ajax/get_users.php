<?php
include('config.php');
$q=str_replace("@","",urldecode($_POST['q']));
if($q==""){ser();}
$sql=$db->prepare("SELECT name,id FROM users WHERE name LIKE ? ORDER BY name DESC LIMIT 8");
$sql->execute(array("%$q%"));
if($sql->rowCount()==0){ser();}
$arr=array();
$k=0;
while($r = $sql->fetch()) {
 $k++;
 $name=$r['name'];
 $img=get("img",$r['id']);
 if(preg_match("/profile\_pics\/om/",$img)){
  $img="http://open.subinsb.com/img/profile_pics/om";
 }elseif(!preg_match("/imgur/",$img) && !preg_match("/akamaihd/",$img) && !preg_match("/google/",$img)){
  $img="http://open.subinsb.com/data/{$r['id']}/img/avatar";
 }
 $arr[$k]['id']=$r['id'];
 $arr[$k]['name']=$name;
 $arr[$k]['avatar']=$img;
}
echo json_encode($arr);
?>
