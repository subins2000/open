<?php
/* A file that uploads images
 * use upload() function with the users' id, filename and the php $_FILE variable as parameters
 * Upload data are inserted in database and not saved in directories
*/
$LS->init();
include_once "$docRoot/source/data/resizer.php";

function upload($uid, $uploadName = false, $FILE){
 	global $OP;
 	$dots       = explode(".", $FILE['name']);
 	$extension  = strtolower($dots[ count($dots)-1 ]);
 	$extensions = array("png", "jpg", "gif", "jpeg");
 	
 	if($uploadName === false){
 		$uploadName = $OP->randStr(5) . "_" . $OP->randStr(5) . "_" . $OP->randStr(5);
 	}
 	
 	/* Check if User Id is numeric and $FILE array contain more than 0 items */
 	if(is_numeric($uid) && is_array($FILE) && count($FILE) > 0){
  		/* Check if File Extension is supported */
  		if(array_search($extension, $extensions) !== false){
			$path   = $FILE['tmp_name'];
			$resize = new ResizeImage($path);
    		$newWidth  	= $resize->imgw();
    		$newHeight 	= $resize->imgh();
			/* We resize to reduce the file size of image */
			$resize->resizeTo($newWidth, $newHeight, 'exact');
			$resize->saveImage($path, 50);
   		
			/* Get the image */
			$imgContent = file_get_contents($path);
			/* Encode the image file */
			$imgContent = base64_encode($imgContent);
  
  			/* For Saving Database Space, we md5 the upload file name */
  			$uploadMD5Name = md5($uploadName);
  			
 		    /* Only do data insertion if the data doesn't exist, else update the already existing value */
			$sql = $OP->dbh->prepare("UPDATE `data` SET `txt`=? WHERE `uid`=? AND `name`=?");
			$sql->execute(array($imgContent, $uid, $uploadMD5Name));
			if($sql->rowCount() == 0){
    			$sql = $OP->dbh->prepare("INSERT INTO `data` (`uid`, `name`, `txt`) VALUES (?, ?, ?)");
    			$sql->execute(array($uid, $uploadMD5Name, $imgContent));
			}
   		
			/* We, for fun add a .png extension to the file name */
			$uploadName .= ".png";
   		
			/* and return the image URL */
			return Open::URL("/data/$uid/$uploadName");
  		}else{
			return "extensionNotSupported";
  		}
 	}else{
  		return false;
 	}
}
?>