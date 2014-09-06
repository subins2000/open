<?php
ini_set("display_errors", "on");
header("Content-type: application/x-javascript");

$requestedFiles = $_GET['f'];
if($requestedFiles != ""){
	$mainDir = realpath(__DIR__);
	$logFile = "$mainDir/changed.txt"; /* File where JS files status are stored */
	$changed = false; /* Is any of the JS file changed from before ? */
	$itc = 0;
	$log = file_get_contents($logFile);
	$log = json_decode($log, true);
 
	$requestedFiles = explode(",", $requestedFiles); /* Becomes an array */
	$requestedFiles = array_unique($requestedFiles);
	foreach($requestedFiles as $v){
		$ltime = filemtime("$mainDir/$v.js");
		/* Check if log file time and JS file modified time are the same */
		if($ltime != $log[$v]){
			$changed = true; /* Yes, a JS file has been changed from before */
			$log[$v] = $ltime; /* Now, change it to the new time */
		}
	}

	$mixedTimeOfFiles = implode("", $log); // Make a single line string of all the timestamps of files
	
	/* Make the ETag */
	/* We use md5 because return value is shorter than SHA */
	$etag = hash("md5", $mixedTimeOfFiles);
	header("ETag: $etag");
	
	/* We make it cachable for the browsers and other mediators */
	header("Cache-Control: public");
 
	/* Was it already cached before by the browser ? The old etag will be sent by the browsers as HTTP_IF_NONE_MATCH. We interpret it */
	$_SERVER["HTTP_IF_NONE_MATCH"] = isset($_SERVER["HTTP_IF_NONE_MATCH"]) && $_SERVER["HTTP_IF_NONE_MATCH"]!=null ? $_SERVER["HTTP_IF_NONE_MATCH"]:501;
 
	if($changed === true || $_SERVER["HTTP_IF_NONE_MATCH"] != $etag){
		foreach($requestedFiles as $reqFile){
			/* We use $itc so that jQuery's $(document).ready can be easily added and closed */
			$itc++; /* We increase the no of files that has been successfully printed */
			if($itc == 1 && $reqFile != "jquery"){
				echo "$(document).ready(function(){";
			}
			echo file_get_contents("$mainDir/$reqFile.js");
			if(count($requestedFiles) == $itc  && $reqFile != "jquery"){
				/* We close the jQuery's $(document).ready here after every file has been successfully printed */
				echo "});";
			}
		}
		/* We updated the changes to log file */
		$newLog = json_encode($log);
		file_put_contents($logFile, $newLog);
	}elseif($_SERVER["HTTP_IF_NONE_MATCH"] == $etag){
		/* "Yes, it's the old version and nothing has been changed" - We send this message to the browser */
		$response->code(304);
	}
}
?>