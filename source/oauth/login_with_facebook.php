<?php
/* OAuth Login With Facebook
 * Copyright 2012 Manuel Lemos
 * Copyright 2014 Subin Siby
 * Login to Open with Facebook
 * Thank you Manuel Lemos
*/

session_start();
/* Include the files */
require "$docRoot/inc/oauth/http.php";
require "$docRoot/inc/oauth/oauth_client.php";
require "$docRoot/inc/oauth/database_oauth_client.php";
require "$docRoot/inc/oauth/mysqli_oauth_client.php";

/* $_GET['c'] have the URL that should be redirected to after oauth logging in */
$_GET['c']				=	isset($_GET['c']) ? $_GET['c'] : "";
$hostParts				=	parse_url($_GET['c']);
$hostParts['host']		= 	isset($hostParts['host']) ? $hostParts['host']:"";

/* We add the session variable containing the URL that should be redirected to after logging in */
$_SESSION['continue']	=	isset($_SESSION['continue']) ? $_SESSION['continue']:"";

if(($_GET['c']=='' && $_SESSION['continue']=='') || $hostParts['host']!=CLEAN_HOST){
 	/* The default Redirect URL open.subinsb.com/home */
 	$_SESSION['continue'] 	=	Open::URL("home");
}else{
 	/* Or the URL that was sent */
 	$_SESSION['continue']	=	$_GET['c'];
}

/* We make an array of Database Details */
$databaseDetails				= 	unserialize(DATABASE);
/* The PHP OAuth Library requires some special items in array, so we add that */
$databaseDetails["password"]	= 	$databaseDetails["pass"];
$databaseDetails["socket"]		= 	"/var/run/mysql/mysql.sock";

$client = new mysqli_oauth_client_class;
$client->database		= 	$databaseDetails;
$client->server			= 	'Facebook';
$client->offline		= 	true;
$client->debug			= 	true;
$client->debug_http		= 	true;
$client->redirect_uri 	= 	Open::URL('/oauth/login_with_facebook');
$client->client_id 		= 	'fbAppID';
$client->client_secret 	= 	'fbAppSecret';
$client->scope 			= 	'email,user_about_me,user_birthday,user_location,publish_stream,status_update';
if(($success = $client->Initialize())){
 	if(($success = $client->Process())){
  		if(strlen($client->authorization_error)){
			$client->error = $client->authorization_error;
			$success = false;
  		}elseif(strlen($client->access_token)){
			$success = $client->CallAPI('https://graph.facebook.com/me', 'GET', array(), array('FailOnAccessError'=>true), $user);
			if($success){
    			$location =  $_SESSION['continue'];
    			$email 	  =  $user->email;
    			$name  	  =  $user->name;
    			$gender	  =  $user->gender;
    			/* Make it DD/MM/YYYY format */
    			$birthday =  date('d/m/Y', strtotime($user->birthday));
    			$image	  =  get_headers("https://graph.facebook.com/me/picture?width=200&height=200&access_token=" . $client->access_token, 1);
    			/* Facebook Redirects the above URL to the image URL, We get that new URL ! PHP is Magic */
    			$image	  =  $image['Location'];
    			
    			/* $sql->rowCount() will be 0 if user doesn't exist */
    			if( $LS->userExists($email) ){
     				/* Since user exist, we log him in */
					$LS->login($email, "");
					$OP->redirect($location);
    			}else{
     				/* An array containing user details that will made in to JSON */
     				$userArray   = array(
     				 	"joined"	=> date("Y-m-d H:i:s"),
     				 	"gen"		=> $gender, /* gen = gender (male/female) */
     				 	"birth"		=> $birthday,
     				 	"img"		=> $image /* img = image */
     				);
     				$json	= json_encode($userArray);
     				$LS->register($email, "", array(
						"name"	=> $name,
						"udata" => $json,
						"seen"	=> ""
					));
					
					/* Login the user */
					$LS->login($email, "");
     				$client->SetUser($id);
     				$OP->redirect($location);
    			}
			}
  		}
 	}
 	$success = $client->Finalize($success);
}
if($client->exit){
 	$OP->ser("Something Happened", "<a href='".$client->redirect_uri."'>Try Again</a>");
}
if(!$success){
?>
 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
 <html>
  <head>
   <title>Error</title>
  </head>
  <body>
   <h1>OAuth client error</h1>
   <pre>Error: <?php echo HtmlSpecialChars($client->error); ?></pre>
  </body>
 </html>
<?php }?>