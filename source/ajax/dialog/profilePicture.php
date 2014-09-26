<?php $LS->init();?>
<!DOCTYPE html>
<html>
	<head>
		<?php /* Ubuntu font in ubuntu,linux machines. Elsewhere it's the browser's default font */?>
		<style>*{font-family:ubuntu;}</style>
	</head>
	<body>
		<h1>Upload A Profile Picture</h1>
		<form method="POST" enctype="multipart/form-data" action="<?php echo $LS->curPageURL();?>">
			<input type="file" name="file"/>
			<button>Upload Picture</button>
			<p>PNG, JPG (JPEG) & GIF images are supported</p>
		</form>
		<?php
		if(isset($_FILES['file'])){
			include_once "$docRoot/source/data/add.php";
			$imageFile		= $_FILES['file'];
			$uploadFileURL	= upload($who, false, $imageFile);
			if($uploadFileURL == "extensionNotSupported"){ /* Was the upload finished successfully ? */
				$OP->ser("Extensions Not Supported", "The extension is not supported. Use supported image extensions");
			}elseif($uploadFileURL){
				/* Save the image URL */
				$OP->save("img", $uploadFileURL);
				/* and show success message */
				$OP->sss("Uploaded Image", "The Image you gave was successfully uploaded & it has been made as your profile picture. <b>Reload Page to see changes.</b>");
			}else{
				/* Or error message */
				$OP->ser("Error Uploading Image", "Something Happened. Try Uploading again.");
			}
		}else{
		?>
			<p>Or Choose one of the following images :</p>
		<?php
		}
		?>
	</body>
</html>