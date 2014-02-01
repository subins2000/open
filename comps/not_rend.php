<?
function show_not($id){
 global $db;
 $sql=$db->prepare("SELECT * FROM notify WHERE id=?");
 $sql->execute(array($id));
 while($r=$sql->fetch()){
  $fid=$r['fid'];
  $img=get("avatar",$fid);
  $name=get("fname",$fid,false);
  list($aid,$pid)=explode("-",$r['post']);
  $amsg=$r['ty']=="cmt" ? "Commented on your post":"";
  if($r['ty']=="fol"){
   $amsg="Is Following You";
  }elseif($r['ty']=="msg"){
   $amsg="Sent A Message";
  }elseif($r['ty']=="men"){
   $amsg="Mentioned You.";
  }
  $alnk=$r['ty']=="cmt" ? "http://open.subinsb.com/view?id=$pid#$aid":"";
  if($r['ty']=="fol"){
   $alnk=get("plink",$r['fid']);
  }elseif($r['ty']=="msg"){
   $alnk="http://open.subinsb.com/chat?id=".$r['fid'];
  }elseif($r['ty']=="men"){
   $alnk="http://open.subinsb.com/view?id=$pid";
  }
  $iuR=$r['red']==0 ? "nred":"";
  $iuT=$r['red']==0 ? "Unread Notification":"";
  $nfs="<a href='$alnk'>";
   $nfs.="<div class='nfsi $iuR' id='$id' title='$iuT'>";
    $nfs.="<div class='left'>";
     $nfs.="<img height='48' width='48' src='$img'/>";
    $nfs.="</div>";
    $nfs.="<div class='right'>";
     $nfs.="<span class='name'>$name</span><br/>";
     $nfs.="<span class='time'>{$r['posted']}</span>";
     $nfs.="<div class='cont'>";
      $nfs.=$amsg;
     $nfs.="</div>";
    $nfs.="</div>";
   $nfs.="</div>";
  $nfs.="</a>";
 }
 $sql=$db->prepare("UPDATE notify SET red='1' WHERE id=?");
 $sql->execute(array($id));
 return $nfs;
}
?>
