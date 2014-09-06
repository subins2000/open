<?php
preg_match_all("/(.*?)\/(.*?)\z/", $_GET['request'], $matches);

/* Get User Id & File Name from the URL */
$userID   = $matches[1][0];
$fileName = $matches[2][0];
$fileCrop = "default";

if(preg_match("/\//", $fileName)){
	$parts 		= explode("/", $fileName);
	$fileName 	= $parts[0];
	$fileCrop 	= $parts[1]; // Whether it should be a small image or not
}

if(substr($fileName, -4) == ".png"){
	$fileName = substr_replace($fileName, "", -4, 4);
}

if($fileName == '' || $userID == ''){
 	$OP->ser();
}else{
 	if($fileCrop != "default"){
		include_once "$docRoot/source/data/resizer.php";
  		$fileCrop	= $fileCrop == "small.png" ? 200 : $fileCrop;
  		$fileCrop	= substr($fileCrop, -4) == ".png" ? substr_replace($fileCrop, "", -4, 4) : $fileCrop;
  		if( !is_numeric($fileCrop) ){
			$OP->ser();
		}
  		$resizeIMG 	= true;
 	}
 	/* md5 encode the file name */
 	$fileName = md5($fileName);
 	
 	$sql=$OP->dbh->prepare("SELECT `txt` FROM `data` WHERE `uid`=? AND `name`=?");
 	$sql->execute(array($userID, $fileName));
 	if($sql->rowCount() == 0){
	  	$OP->ser();
 	}
 	/* The File Data encoded with base64 */
 	$fileData = $sql->fetchColumn();
 	
 	/* The timestamp of the next 10 year */
 	$years 	= strtotime("+5 years");
 
 	/* Set Headers */
 	header("Cache-Control: public");
 	header("Content-type: image/png"); // We only server PNG images
 	
 	/* Caching Headers */
 	$etag = hash("md5", $fileName.$fileCrop);
	header("ETag: $etag");
 	
 	/* Was it already cached before by the browser ? The old etag will be sent by the browsers as HTTP_IF_NONE_MATCH. We interpret it */
	$_SERVER["HTTP_IF_NONE_MATCH"] = isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"]!=null ? $_SERVER["HTTP_IF_NONE_MATCH"]:501;
 	if($_SERVER["HTTP_IF_NONE_MATCH"] == $etag){
		/* "Yes, it's the old version and nothing has been changed" - We send this message to the browser */
		header("HTTP/1.1 304 Not Modified");
	}
 	
 	/* Get the original image file */
 	$fileData = base64_decode($fileData);
 
 	/* Serve the small image if it's the one that is requested */
 	if( isset($resizeIMG) ){
  		/* make a temporary file and replace it with the original image */
  		$temp 	 = tempnam("/tmp", "FOO");
  		file_put_contents($temp, $fileData);
  		$resize	 = new ResizeImage($temp);
  		
  		/* Make the small image resize to small according to the original ratio */
  		$total	  	= $resize->imgw() + $resize->imgh();
    	$newWidth  	= $resize->imgw() / $total * $fileCrop;
    	$newHeight 	= $resize->imgh() / $total * $fileCrop;
    	
    	if($newWidth > $resize->imgw() || $newHeight > $resize->imgh()){
			$OP->ser();
		}
    	
    	/* Resize & Save */
  		$resize->resizeTo($newWidth, $newHeight, 'exact');
  		$resize->saveImage($temp, 80);
  		$fileData = file_get_contents($temp);
 	}
 	/* Output the file data */
 	echo $fileData;
}
?>