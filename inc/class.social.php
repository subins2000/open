<?php
require_once "$docRoot/inc/oauth/http.php";
require_once "$docRoot/inc/oauth/oauth_client.php";
require_once "$docRoot/inc/oauth/database_oauth_client.php";
require_once "$docRoot/inc/oauth/mysqli_oauth_client.php";

class SocialPost extends Open {
  
  private $client = null;
  
  public function __construct($who){
    $databaseDetails = unserialize(DATABASE);
    /* The PHP OAuth Library requires some special items in array, so we add that */
    $databaseDetails["password"] = $databaseDetails["pass"];
    $databaseDetails["socket"] = "/var/run/mysqld/mysqld.sock";
    
    if(is_numeric($who)){
      $this->client = new mysqli_oauth_client_class;
      $this->client->user = $who;
      $this->client->database = $databaseDetails;
    }
  }
  
  /* Post To Twitter */
  public function postToTwitter($post){
     $this->client->server = 'Twitter';
     $this->client->offline = true;
     $this->client->debug = false;
     $this->client->debug_http = true;
     $this->client->client_id = $GLOBALS['cfg']['twitter']['api_key'];
     $this->client->client_secret = $GLOBALS['cfg']['twitter']['api_secret'];
     
     if( $success = $this->client->Initialize() ){
        $success = $this->client->CallAPI(
         "https://api.twitter.com/1.1/statuses/update.json",
         'POST',
         array(
            'status' => $post,
         ),array(
            'FailOnAccessError'=>true
         ), $user);
     }
     $success = $this->client->Finalize($success);
  }
  
  /* Post to Facebook */
  public function postToFacebook($post, $privacy){
    /* Give Privacy Settings to Facebook as an array */
    
    /* Privacy = Everyone */
    if($privacy == 'pub'){
        $privacySetting = array('value' => 'EVERYONE');
     }
     /* Privacy = Friends */
     if($privacy == 'fri'){
        $privacySetting = array('value' => 'ALL_FRIENDS');
     }
     /* Privacy = Me Only */
     if($privacy == 'meo'){
        $privacySetting = array('value' => 'CUSTOM', 'friends' => "SELF");
     }
     
     $this->client->server = 'Facebook';
     $this->client->offline = true;
     $this->client->debug = false;
     $this->client->debug_http = false;
     $this->client->client_id = $GLOBALS['cfg']['facebook']['app_id'];
     $this->client->client_secret = $GLOBALS['cfg']['facebook']['app_secret'];
     
     if( $success = $this->client->Initialize() ){
        $success = $this->client->CallAPI(
         "https://graph.facebook.com/me/feed",
         'POST',
         array(
            'message' => $post,
            'privacy' => $privacySetting
         ),array(
            'FailOnAccessError' => true
         ), $user);
     }
     $success = $this->client->Finalize($success);
  }
}
?>
