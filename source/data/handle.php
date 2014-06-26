<?
$_GET['request'] = substr_replace($_GET['request'], "", 0, 5);
preg_match_all("/(.*?)\/(.*?)\z/", $_GET['request'], $matches);

/* Get User Id & File Name from the URL */
$userId   = $matches[1][0];
$fileName = $matches[2][0];
if(substr($fileName, -4)==".png"){
	$fileName = substr_replace($fileName, "", -4, 4);
}

if($fileName=='' || $userId==''){
 	$OP->ser();
}else{
 	if(substr($fileName, -5, 5) == "small"){
  		include_once "$docRoot/source/data/resizer.php";
  		$fileName = substr_replace($fileName, "", -10); // Replace the last .png/small.png part
  		$small	 = 1;
 	}
 	/* md5 encode the file name */
 	$fileName = md5($fileName);
 	
 	$sql=$OP->dbh->prepare("SELECT `txt` FROM `data` WHERE `uid`=? AND `name`=?");
 	$sql->execute(array($userId, $fileName));
 	if($sql->rowCount()==0){
	  		$OP->ser();
 	}
 	/* The File Data encoded with base64 */
 	$fileData = $sql->fetchColumn();
 	
 	/* The timestamp of the next 10 year */
 	$years 	= strtotime("+10 years");
 
 	/* Set Headers */
 	header("Expires: " . gmdate('D, d M Y H:i:s', $years) . ' GMT');
 	header("Cache-Control: max-age=" . date("s", $years) . ", public");
 	/* We only server PNG images */
 	header("Content-type: image/png");
 	header('Vary: Accept');
 	
 	/* Get the original image file */
 	$fileData = base64_decode($fileData);
 
 	/* Serve the small image if it's the one that is requested */
 	if(isset($small)){
  		/* make a temporary file and replace it with the original image */
  		$temp 	 = tempnam("/tmp", "FOO");
  		file_put_contents($temp, $fileData);
  		$resize 	 = new ResizeImage($temp);
  		
  		/* Make the small image resize to small according to the original ratio */
  		$total	  = $resize->imgw() + $resize->imgh();
    	$newWidth  = $resize->imgw() / $total * 200;
    	$newHeight = $resize->imgh() / $total * 200;
    	
    	/* Resize & Save */
  		$resize->resizeTo($newWidth, $newHeight, 'exact');
  		$resize->saveImage($temp, 100);
  		$fileData = file_get_contents($temp);
 	}
 	/* Output the file data */
 	echo $fileData;
}
?>