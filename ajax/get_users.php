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
 $img=get("avatar",$r['id']);
 $arr[$k]['id']=$r['id'];
 $arr[$k]['name']=$name;
 $arr[$k]['avatar']=$img;
}
echo json_encode($arr);
?>
