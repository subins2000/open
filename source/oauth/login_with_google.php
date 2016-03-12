<?php
/* OAuth Login With Google
 * Copyright 2012 Manuel Lemos
 * Copyright 2014 Subin Siby
 * Login to Open with google
 * Thank you Manuel Lemos
*/

session_start();
/* Include the files */
include "$docRoot/inc/oauth/http.php";
include "$docRoot/inc/oauth/oauth_client.php";

/* We add the session variable containing the URL that should be redirected to after logging in */
$_SESSION['continue'] = isset($_SESSION['continue']) ? $_SESSION['continue']:"";

/* $_GET['c'] have the URL that should be redirected to after oauth logging in */
$_GET['c'] = isset($_GET['c']) ? urldecode($_GET['c']) : "";

if($_GET['c'] == '' && $_SESSION['continue'] == ''){
  /* The default Redirect URL open.dev/home */
  $_SESSION['continue'] = Open::URL("home");
}else if($_GET['c'] != ''){
  /* Or the URL that was sent */
  $hostParts = parse_url($_GET['c']);
  $hostParts['host'] = isset($hostParts['host']) ? $hostParts['host']:"";
  
  if($hostParts['host'] != CLEAN_HOST){
    $_SESSION['continue'] = Open::URL("home");
  }else{
    $_SESSION['continue'] = urldecode($_GET['c']);
  }
}

/* We make an array of Database Details */
$databaseDetails = unserialize(DATABASE);
/* The PHP OAuth Library requires some special items in array, so we add that */
$databaseDetails["password"] = $databaseDetails["pass"];
$databaseDetails["socket"] = "/var/lib/mysql/mysql.sock";

$client = new oauth_client_class;
$client->database = $databaseDetails;
$client->server = 'Google';
$client->offline = false;
$client->debug = false;
$client->debug_http = false;
$client->redirect_uri = Open::URL('/oauth/login_with_google');
$client->client_id = $GLOBALS['cfg']['google']['client_id'];
$client->client_secret = $GLOBALS['cfg']['google']['client_secret'];
$client->scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/plus.me';
if(($success = $client->Initialize())){
   if(($success = $client->Process())){
      if(strlen($client->authorization_error)){
      $client->error = $client->authorization_error;
      $success = false;
      }elseif(strlen($client->access_token)){
      $success = $client->CallAPI('https://www.googleapis.com/oauth2/v2/userinfo', 'GET', array(), array('FailOnAccessError' => true), $user);
      }
   }
   $success = $client->Finalize($success);
}
if($client->exit){
   $OP->ser("Something Happened", "<a href='".$client->redirect_uri."'>Try Again</a>");
}
/* A function to validate date */
function validateDate($date){
      $d = DateTime::createFromFormat('Y-m-d', $date);
      return $d && $d->format('Y-m-d') == $date;
}
if($success){
    $location = $_SESSION['continue'];
    $email = $user->email;
    $name = $user->name;
    $gender = $user->gender;
    /* Make it DD/MM/YYYY format */
    if(isset($user->birthday) && validateDate($user->birthday)){
      $birthday = date('d/m/Y', strtotime($user->birthday));
    }
    $image = $user->picture;
      
    /* We now check if the E-Mail is already registered */
    if( \Fr\LS::userExists($email) ){
       /* Since user exist, we log him in */
       \Fr\LS::login($email, "");
       $OP->redirect($location);
    }else{
       /* An array containing user details that will made in to JSON */
       $userArray = array(
          "joined" => date("Y-m-d H:i:s"),
         "gen" => $gender, /* gen = gender (male/female) */
          "img" => $image /* img = image */
       );
       if(isset($birthday)){
         $userArray["birth"] = $birthday;
       }
       $json = json_encode($userArray);
       
       \Fr\LS::register($email, "", array(
      "name" => $name,
      "udata" => $json,
      "seen" => ""
    ));
       /* Login the user */
       \Fr\LS::login($email, "");
       $OP->redirect($location);
  }
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
