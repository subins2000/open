<?php
/**
 * OpenAuth Class - Opth
 */
class Opth {

  protected static $OP = "";
  public static $scopes = array(
    "email-send" => "Send Important Emails To You",
    "email-subscription" => "Subscribe to the Site (Receive newsletters and stuff)",
    "read-name" => "Read Your Name"
  );
  public static $uid, $sid = "";
  
  public static function init(){
    global $OP, $LS;
    self::$OP = $OP;
    if(\Fr\LS::$loggedIn){
      self::$uid = \Fr\LS::$user; // The user's ID
    }
  }
  
  public static function readable_scope($scope){
    return self::$scopes[$scope];
  }
  
  /**
   * Check if a site exists
   */
  public static function exists($api_key, $api_secret){
    $sql = self::$OP->dbh->prepare("SELECT `id` FROM `opth_sites` WHERE `api_key` = ? AND `api_secret` = ?");
    $sql->execute(array($api_key, $api_secret));
    return $sql->rowCount() == 0 ? false : $sql->fetchColumn();
  }
  
  /**
   * Server means the SITE to which user is connecting to
   * Get the server's info as array. Also, intialize the user to the server
   */
  public static function server($api_key, $redirect_url){
    $sql = self::$OP->dbh->prepare("SELECT * FROM `opth_sites` WHERE `api_key` = ? AND `redirect_url` LIKE ?");
    $sql->execute(array($api_key, "%{$redirect_url}%"));
    if($sql->rowCount() == 0){
      return false;
    }else{
      $data = $sql->fetch(PDO::FETCH_ASSOC);
      self::$sid = $data["id"];
      return $data;
    }
  }
  
  /**
   * Check if a user is authorized already
   */
  public static function authorized($access_token = false){
    if($access_token == false){
      $sql = self::$OP->dbh->prepare("SELECT COUNT(1), `expiry` FROM `opth_session` WHERE `uid` = ? AND `sid` = ?");
      $sql->execute(array(self::$uid, self::$sid));
    }else{
      $sql = self::$OP->dbh->prepare("SELECT COUNT(1), `expiry` FROM `opth_session` WHERE `access_token` = ? AND `sid` = ?");
      $sql->execute(array($access_token, self::$sid));
    }
    $results = $sql->fetch();
    if($results[0] == '0'){
      return false;
    }else{
      $expiry = $results[1];
      if(time() >= $expiry){
        /**
         * Expired
         */
        return false;
      }else{
        return true;
      }
    }
  }
  
  /**
   * Authorize a user
   */
  public static function authorize($permissions, $server_token, $expire_refresh = false){
    $sql = self::$OP->dbh->prepare("SELECT `expiry`, `server_token` FROM `opth_session` WHERE `uid` = ? AND `sid` = ?");
    $sql->execute(array(self::$uid, self::$sid));
    
    if($sql->rowCount() != 0){
      $results = $sql->fetch();
      $expiry = $results[0];

      if(time() >= $expiry){
        if($expire_refresh){
          $sql = self::$OP->dbh->prepare("UPDATE `opth_session` SET `expiry` = ?, `server_token` = ? WHERE `uid` = ? AND `sid` = ?");
          $sql->execute(array(strtotime("+1 month"), $server_token, self::$uid, self::$sid));
          return true;
        }else{
          return "expired";
        }
      }else if($results[1] != $server_token){
        $sql = self::$OP->dbh->prepare("UPDATE `opth_session` SET `server_token` = ? WHERE `uid` = ? AND `sid` = ?");
        $sql->execute(array($server_token, self::$uid, self::$sid));
        return true;
      }
    }else{
      $access_token = hash("sha256", self::$OP->randStr(25));
    
      $sql = self::$OP->dbh->prepare("INSERT INTO `opth_session` (`server_token`, `uid`, `sid`, `access_token`, `created`, `expiry`, `permissions`) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $sql->execute(array(
        $server_token,
        self::$uid,
        self::$sid,
        $access_token,
        time(),
        strtotime("+1 month"),
        serialize($permissions)
      ));
    }
  }
  
  /**
   * Register a site
   * $title - The name of the site
   * $url - The site's webpage URL
   * $description - A short info on the site. Max 50 chars
   * $redirect_url - Contains all the redirect URls separated by a newline
   */
  public static function register($title, $url, $description, $redirect_url){
    global $who;
    $urlParts = parse_url($url);
    $urlHost = $urlParts['host'];
    $sql = self::$OP->dbh->prepare("SELECT COUNT(`id`) FROM `opth_sites` WHERE `url` = ? OR `title` LIKE ?");
    $sql->execute(array($urlHost, "%{$title}%"));
    
    if($sql->fetchColumn() != 0){
      return "exists";
    }else{
      /**
       * Make an API Key and API Secret string
       */
      $api_key = self::$OP->randStr(40);
      $api_secret = hash("sha256", self::$OP->randStr(50));
            
      $sql = self::$OP->dbh->prepare("INSERT INTO `opth_sites` (`uid`, `api_key`, `api_secret`, `title`, `url`, `description`, `redirect_url`) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $success = $sql->execute(array($who, $api_key, $api_secret, $title, $url, $description, $redirect_url));
      if($sql->rowCount() != 0){
        return true;
      }else{
        return false;
      }
    }
  }
}
Opth::init();
