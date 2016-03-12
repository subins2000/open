<?php
/**
 * Uncomment the following line to display PHP errors
 */
ini_set("display_errors", "on");

/**
 * The URL of Open. No '/' at the end
 */
define("O_URL", "http://open.sim");

/**
 * Hostname (URL without protocol [http, https] and slashes)
 */
define("CLEAN_HOST", "open.sim");

/* -- Database Configuration STARTS -- */
  
define("DATABASE", serialize(array(
  "host" => getenv('OPENSHIFT_MYSQL_DB_HOST'),
  "port" => getenv('OPENSHIFT_MYSQL_DB_PORT'),
  "name" => "open",
  "user" => getenv('OPENSHIFT_MYSQL_DB_USERNAME'),
  "pass" => getenv('OPENSHIFT_MYSQL_DB_PASSWORD')
)));
    
/**
 * We serialize the DATABASE constant value becuase we can't make arrays as values
 */

/**
 * Keys
 */
$cfg = array(
  'keys' => array(
    'email_verification' => getenv('OPEN_keys_email_verification'),
    'master_key' => getenv('OPEN_keys_master_key')
  ),
  
  'logsys' => array(
    'cookie_key' => getenv('OPEN_logsys_cookie_key'),
    'password_salt' => getenv('OPEN_logsys_password_salt')
  ),
  
  'facebook' => array(
    'app_id' => '670472332987070',
    'app_secret' => getenv('OPEN_facebook_app_secret')
  ),
  
  'google' => array(
    'client_id' => '575210830217-2s0n7ik12s4d0pjju408efc37o5p0kgu.apps.googleusercontent.com',
    'client_secret' => getenv('OPEN_google_client_secret')
  ),
  
  'twitter' => array(
    'api_key' => 'IrZGE5zMhO2W2wsyHjwNyQ',
    'api_secret' => getenv('OPEN_twitter_api_secret')
  ),
  
  'email_accounts' => array(
    1 => array("subinsiby1@gmail.com", getenv("OPEN_email_accounts_1")),
    2 => array("friendshoodhelp@gmail.com", getenv("OPEN_email_accounts_2")),
    3 => array("openautomail@gmail.com", getenv("OPEN_email_accounts_3"))
  )
);
  
/* -- Database Configuration ENDS -- */
?>
