<?php
/*
.---------------------------------------------------------------------------.
|  Software: PHP Login System - PHP logSys                                  |
|   Version: 0.2                                                            |
|   Contact: http://github.com/subins2000/logsys  (also subinsb.com)        |
|      Info: http://github.com/subins2000/logsys                            |
|   Support: http://subinsb.com/ask/php-logsys                              |
| ------------------------------------------------------------------------- |
|    Author: Subin Siby (project admininistrator)                           |
| Copyright (c) 2014, Subin Siby. All Rights Reserved.                      |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            http://www.gnu.org/licenses/gpl-3.0.html                       |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/
ini_set("display_errors", "on");

class LoginSystem {

	/* Start Config */

	private $dbhost 		= "";	// Host Name
	private $dbport 		= "3306";	//Port
	private $dbuser 		= "";	// MySQL Database Username
	private $dbpass 		= "";	// MySQL Database Password
	private $dbname 		= "";	// Database Name
	private $dbtable 		= "users";	// Your Users Table
 
	private $secureKey		= "cantMakePublic";	// A Secure Key For Cookie Encryption. Don't make this public
	private $passwordSalt 	= "cantMakePublic";	// Secret Password Salt. Only change once before setting user registration public.
	private $company		= "Open";	// Company for name for including in emails
	var $phpsessionstart	= true;	// Should I Start A PHP Session
	var $emailLogin			= false;	// Make Login With Username & E-Mail Possible
	var $rememberMe			= true;	// Add Remember Me Feature.
	
		/* Extra Settings - Set the following	variables only if you're going to use $LS->init() */
		
		public $staticPages	= array(
			"/", "/register"
 		);	// Pages that doesn't require logging in (exclude login page) (but include register page if you want)
 	
		private $loginPage	= "/login"; // The login page. ex : /login.php or /accounts/login.php
		private $homePage	= "/home";	// The home page. The main page for logged in users. Redirects to here when logs in
	
		/* End Extra Settings */
	
	/* End Config */
 
	public $loggedIn 		= false;
	public $db				= true;
	public $user			= false;
	private $initCalled		= false;
	private $cookie;
	private $session;
	private $remCook;
	private $dbh;
 
	public function __construct(){
		$database = unserialize(DATABASE);
		$this->dbhost = $database["host"];
  		$this->dbport = $database["port"];
  		$this->dbuser = $database["user"];
  		$this->dbpass = $database["pass"];
  		$this->dbname = $database["name"];
		
		if($this->phpsessionstart == true){
			session_start();
		}
		/* Try connecting to Database Server */
		try{
			/* Merge the login page to the pages array that doesn't need logging in */
			array_push($this->staticPages, $this->loginPage);
			
			$this->dbh		= new PDO("mysql:dbname={$this->dbname};host={$this->dbhost};port={$this->dbport}", $this->dbuser, $this->dbpass);
			$this->db 		= true;
			$this->cookie	= isset($_COOKIE['logSyslogin']) ? $_COOKIE['logSyslogin'] : false;
			$this->session  = isset($_SESSION['logSyscuruser']) ? $_SESSION['logSyscuruser'] : false;
			$this->remCook  = isset($_COOKIE['logSysrememberMe']) ? $_COOKIE['logSysrememberMe'] : false;
			
			$encUserID 		= hash("sha256", "{$this->secureKey}{$this->session}{$this->secureKey}");
			$this->loggedIn = $this->cookie == $encUserID ? true : false;
			
			/* If there is a Remember Me Cookie and the user is not logged in, then log in the user with the ID in the remember cookie, if it matches with the secure hashed value in logSyslogin cookie */
			if($this->rememberMe === true && isset($this->remCook) && $this->loggedIn === false){
				
				$encUserID		= hash("sha256", "{$this->secureKey}{$this->remCook}{$this->secureKey}");
				$this->loggedIn = $this->cookie == $encUserID ? true : false;
				
				if($this->loggedIn === true){
					$_SESSION['logSyscuruser'] = $this->remCook;
				}
			}
			
			$this->user = $this->session;
			return true;
			
		}catch( PDOException $e ) {
			return false;
		}
	}
	
	/* A function that will automatically redirect user according to his/her login status */
	public function init() {
		if( $this->loggedIn && array_search($this->curPage(), $this->staticPages) !== false ){
			$this->redirect($this->homePage);
		}elseif( !$this->loggedIn && array_search($this->curPage(), $this->staticPages) === false ){
			$this->redirect($this->loginPage);
		}
		$this->initCalled = true;
	}
	
	/* A function to login the user with the username and password given. */
	public function login($username, $password, $cookies = true){
		if($this->db === true){
			
			/* We Add LIMIT to 1 in SQL query because we need to just get an array of data with key as the column name. Nothing else. */
			if($this->emailLogin === true){
				$query = "SELECT `id`, `password`, `password_salt` FROM `{$this->dbtable}` WHERE `username`=:login OR `email`=:login ORDER BY `id` LIMIT 1";
			}else{
				$query = "SELECT `id`, `password`, `password_salt` FROM `{$this->dbtable}` WHERE `username`=:login ORDER BY `id` LIMIT 1";
			}
			
			$sql = $this->dbh->prepare($query);
			$sql->bindValue(":login", $username);
			$sql->execute();
			
			if($sql->rowCount()==0){
				return false;
			}else{
				/* Get the user details */
				$rows		= $sql->fetch(PDO::FETCH_ASSOC);
				$us_id		= $rows['id'];
				$us_pass 	= $rows['password'];
				$us_salt 	= $rows['password_salt'];
				$saltedPass = hash('sha256', "{$password}{$this->passwordSalt}{$us_salt}");
				
				if($saltedPass == $us_pass || $password == ""){
					if($cookies === true){
						
						$_SESSION['logSyscuruser'] = $us_id;
						setcookie("logSyslogin", hash("sha256", $this->secureKey.$us_id.$this->secureKey), time()+3600*99*500, "/");
						
						if( isset($_POST['remember_me']) && $this->rememberMe === true ){
							setcookie("logSysrememberMe", $us_id, time()+3600*99*500, "/");
						}
						$this->loggedIn = true;
						if( $this->initCalled ){
							$this->redirect($this->homePage);
						}
					}
					return true;
				}else{
					return false;
				}
			}
		}
	}
	
	/* A function to register a user with passing the username, password and optionally any other additional fields. */
	public function register( $id, $password, $other = array() ){
		if( $this->userExists($id) && (isset($other['email']) && $this->userExists($other['email'])) ){
			return "exists";
		}else{
			$randomSalt	= $this->rand_string(20);
			$saltedPass	= hash('sha256', "{$password}{$this->passwordSalt}{$randomSalt}");
			
			if( count($other) == 0 ){
				/* If there is no other fields mentioned, make the default query */
				$sql = $this->dbh->prepare("INSERT INTO `{$this->dbtable}` (`username`, `password`, `password_salt`) VALUES(:username, :password, :passwordSalt)");
			}else{
				/* if there are other fields to add value to, make the query and bind values according to it */
				$keys	 = array_keys($other);
				$columns = implode(",", $keys);
				$colVals = implode(",:", $keys);
				$sql	 = $this->dbh->prepare("INSERT INTO `{$this->dbtable}` (`username`, `password`, `password_salt`, $columns) VALUES(:username, :password, :passwordSalt, :$colVals)");
				foreach($other as $key => $value){
					//$value = htmlspecialchars($value);
					$sql->bindValue(":$key", $value);
				}
			}
			/* Bind the default values */
			$sql->bindValue(":username", $id);
			$sql->bindValue(":password", $saltedPass);
			$sql->bindValue(":passwordSalt", $randomSalt);
			$sql->execute();
			return true;
		}
	}
	
	/* Logout the current logged in user by deleting the cookies and destroying session */
	public function logout(){
		session_destroy();
		setcookie("logSyslogin", "", time()-3600, "/");
		setcookie("logSysrememberMe", "", time()-3600, "/");
		$this->redirect($this->loginPage);
		return true;
	}
	
	/* A function to handle the Forgot Password process */
	public function forgotPassword(){
		
		$curStatus = "initial";	// The Current Status of Forgot Password process
		$identName = $this->emailLogin === false ? "Username" : "Username / E-Mail";
		
		if( !isset($_POST['logSysforgotPass']) && !isset($_GET['resetPassToken']) && !isset($_POST['logSysforgotPassRePass']) ){
			$html='<form action="'.$this->curPage().'" method="POST">';
				$html.="<label>$identName<br/><input type='text' id='loginSysIdentification' placeholder='Enter your $identName' size='25' name='identification'/></label>";
				$html.="<br/><button name='logSysforgotPass' type='submit'>Reset Password</button>";
			$html.="</form>";
			echo $html;
			$curStatus = "resetPasswordForm"; // The user had moved to the reset password form ie she/he is currently seeing the forgot password form.
			
		}elseif( isset($_GET['resetPassToken']) && !isset($_POST['logSysforgotPassRePass']) ){
			/* The user gave the password reset token. Check if the token is valid. */
			$_GET['resetPassToken'] = urldecode($_GET['resetPassToken']);
			$sql = $this->dbh->prepare("SELECT `uid` FROM `resetTokens` WHERE `token` = ?");
			$sql->execute(array($_GET['resetPassToken']));
			
			if( $sql->rowCount() == 0 || $_GET['resetPassToken'] == "" ){
				echo "<h3>Error : Wrong/Invalid Token</h3>";
				$curStatus = "invalidToken"; // The token user gave was not valid
			}else{
				/* The token is valid, display the new password form */
				$html  = "<p>The Token key was Authorized. Now, you can change the password</p>";
				$html .= "<form action='{$this->curPage()}' method='POST'>";
					$html	.=	"<input type='hidden' name='token' value='{$_GET['resetPassToken']}'/>";
					$html	.=	"<label>New Password<br/><input type='password' name='password'/></label><br/>";
					$html	.=	"<label>Retype Password<br/><input type='password' name='password2'/></label><br/>";
					$html	.=	"<button name='logSysforgotPassRePass'>Reset Password</button>";
				$html	.=	"</form>";
				echo $html;
				$curStatus = "changePasswordForm"; // The token was correct, displayed the change/new password form.
			}
		}elseif( isset($_POST['logSysforgotPassRePass']) ){
			$_POST['token'] = urldecode($_POST['token']);
			$sql = $this->dbh->prepare("SELECT `uid` FROM `resetTokens` WHERE `token` = ?");
			$sql->execute(array($_POST['token']));
			
			if( $sql->rowCount()==0 || $_POST['token']=="" ){
				echo "<h3>Error : Wrong/Invalid Token</h3>";
				$curStatus = "invalidToken"; // The token user gave was not valid
			}else{
				if( $_POST['password'] != $_POST['password2'] || $_POST['password']=="" || $_POST['password2']=="" ){
					echo "<h3>Error : Passwords Don't Match Or Passwords Left Blank</h3>";
					$curStatus = "passwordDontMatch"; // The new password and retype password submitted didn't match
				}else{
					
					$_POST['newPassword'] = $_POST['password2'];
					$this->user			  = $sql->fetchColumn();
					$this->loggedIn		  = true; // We must create a fake assumption that the user is logged in to change the password as $LS->changePassword() requires the user to be logged in.
					
					if( $this->changePassword($this->secureKey) ){
						$sql			= $this->dbh->prepare("DELETE FROM `resetTokens` WHERE `uid`=?");
						$sql->execute(array($this->user));
						$this->user		= false;
						$this->loggedIn = false;
						echo "<h3>Success : Password Reset Successful</h3><p>You may now login with your new password.</p>";
						$curStatus = "passwordChanged"; // The password was successfully changed
					}
				}
			}
		}else{
			/* Check if username/email is provided and if it's valid and exists */
			$identification = isset($_POST['identification']) ? $_POST['identification']:"";
			if($identification == ""){
				echo "<h3>Error : $identName not provided</h3>";
				$curStatus = "identityNotProvided"; // The identity was not given
			}else{
				if($this->emailLogin){
					$sql = $this->dbh->prepare("SELECT `email`, `id` FROM `{$this->dbtable}` WHERE `username`=:login OR `email`=:login");
				}else{
					$sql = $this->dbh->prepare("SELECT `username`, `id` FROM `{$this->dbtable}` WHERE `username`=:login");
				}
				$sql->bindValue(":login", $identification);
				$sql->execute();
				if($sql->rowCount() == 0){
					echo "<h3>Error : User Not Found</h3>";
					$curStatus = "userNotFound"; // The user with the identity given was not found in the users database
				}else{
					$rows  = $sql->fetch(PDO::FETCH_ASSOC);
					$email = $this->emailLogin ? $rows['email'] : $rows['username']; // Assuming the column `username` contains the email
					$uid   = $rows['id'];
					$token = $this->rand_string(40);
					$sql   = $this->dbh->prepare("INSERT INTO `resetTokens` (`token`, `uid`, `requested`) VALUES (?, ?, NOW())");
					$sql->execute(array($token, $uid));
					$encodedToken = urlencode($token);
					
					/* Prepare the email to be sent */
					$subject = "Reset Password";
					$body	 = "You requested for resetting your password on {$this->company}. For this, please click the following link :
					<blockquote>
						<a href='{$this->curPageURL()}?resetPassToken={$encodedToken}'>Reset Password : {$token}</a>
					</blockquote>";
					$this->sendMail($email, $subject, $body);	/* Change mail() function to something else if you like */
					echo "<p>An email has been sent to your email inbox with instructions. Check Your Mail Inbox and SPAM Folders.</p><p>You can close this window.</p>";
					$curStatus = "emailSent"; // E-Mail has been sent
				}
			}
		}
		return $curStatus;
	}
	
	/* A function that handles the logged in user to change her/his password */
	public function changePassword($parent = ""){
		$curStatus = "initial";	// The Current Status of Change Password action
		if($this->loggedIn){
			if( $parent == $this->secureKey && isset($_POST['newPassword']) && $_POST['newPassword'] != "" ){
				$randomSalt	= $this->rand_string(20);
				$saltedPass = hash('sha256',$_POST['newPassword'].$this->passwordSalt.$randomSalt);
				$sql		= $this->dbh->prepare("UPDATE `{$this->dbtable}` SET `password` = ?, `password_salt` = ? WHERE `id` = ?");
				$sql->execute(array($saltedPass, $randomSalt, $this->user));
				return true;
			}elseif( !isset($_POST['logSysChangePassword']) ){
				$html = "<form action='".$this->curPage()."' method='POST'>";
					$html .= "<label>Current Password<br/><input type='password' name='curpass'/></label><br/>";
					$html .= "<label>New Password<br/><input type='password' name='newPassword'/></label><br/>";
					$html .= "<label>Retype New Password<br/><input type='password' name='newPassword2'/></label><br/>";
					$html .= "<button name='logSysChangePassword' type='submit'>Change Password</button>";
				$html .= "</form>";
				echo $html;
				$curStatus = "changePasswordForm"; // The form for changing password is shown now
			}elseif(isset($_POST['logSysChangePassword'])){
				if( isset($_POST['newPassword']) && $_POST['newPassword']!="" && isset($_POST['newPassword2']) && $_POST['newPassword2']!="" && isset($_POST['curpass']) ){
					$curpass	  = $_POST['curpass'];
					$newPassword  = $_POST['newPassword'];
					$newPassword2 = $_POST['newPassword2'];
					$sql		  = $this->dbh->prepare("SELECT `username` FROM `{$this->dbtable}` WHERE `id` = ?");
					$sql->execute(array($this->user));
					$curuserUsername = $sql->fetchColumn();
					if($this->login($curuserUsername, $curpass, false)){
						if($newPassword != $newPassword2){
							echo "<h3>Error : Password Mismatch</h3>";
							echo "<a href='{$this->curPageURL()}'>Try again</a>";
							$curStatus = "newPasswordMismatch"; // The Password's don't match (New Password & Retype Password field)
						}else{
							$this->changePassword($this->secureKey);
							echo "<h3>Success : Password Changed Successful</h3>";
							$curStatus = "passwordChanged"; // Password changed
						}
					}else{
						echo "<h3>Error : Current Password Was Wrong</h3>";
						echo "<a href='{$this->curPageURL()}'>Try again</a>";
						$curStatus = "currentPasswordWrong"; // The current password entered was wrong
					}
				}else{
					echo "<h3>Error : Password Fields was blank</h3>";
					echo "<a href='{$this->curPageURL()}'>Try again</a>";
					$curStatus = "newPasswordFieldsBlank"; // Blank new password field
				}
			}
		}else{
			echo "<h3>Error : Not Logged In</h3>";
			$curStatus = "notLoggedIn"; // Not logged In
		}
		return $curStatus;
	}
	
	/* Check if user exists with ther username/email given */
	public function userExists($username){
		if($this->emailLogin === true){
			$query = "SELECT `id` FROM `{$this->dbtable}` WHERE `username`=:login OR `email`=:login ORDER BY `id` LIMIT 1";
		}else{
			$query = "SELECT `id` FROM `{$this->dbtable}` WHERE `username`=:login ORDER BY `id` LIMIT 1";
		}
		$sql = $this->dbh->prepare($query);
		$sql->execute(array(
			":login" => $username
		));
		return $sql->rowCount() == 0 ? false : true;
	}
	
	/* Fetches data of user in database. Returns a single value or an array of value according to parameteres given to the function */
	public function getUser($what = "*", $user = null){
		if($user == null){
			$user = $this->user;
		}
		if( is_array($what) ){
			$columns = implode("`,`", $what);
			$columns	= "`{$columns}`";
		}else{
			$columns = $what != "*" ? "`$what`" : "*";
		}
		
		$sql = $this->dbh->prepare("SELECT {$columns} FROM `{$this->dbtable}` WHERE `id`=? ORDER BY `id` LIMIT 1");
		$sql->execute(array($user));
		
		$data = $sql->fetch(PDO::FETCH_ASSOC);
		if( !is_array($what) ){
			$data = $what == "*" ? $data : $data[$what];
		}
		return $data;
	}
	
	/* Updates the user data */
	public function updateUser($toUpdate = array(), $user = null){
		if( is_array($toUpdate) && !isset($toUpdate['id']) ){
			if($user == null){
				$user = $this->user;
			}
			$columns = "";
			foreach($toUpdate as $k => $v){
				$columns .= "`$k` = :$k, ";
			}
			$columns = substr($columns, 0, -2); // Remove last ","
		
			$sql = $this->dbh->prepare("UPDATE `{$this->dbtable}` SET {$columns} WHERE `id`=:id");
			$sql->bindValue(":id", $user);
			foreach($toUpdate as $key => $value){
					$value = htmlspecialchars($value);
					$sql->bindValue(":$key", $value);
				}
			$sql->execute();
			
		}else{
			return false;
		}
	}
	
	/* Returns a string which shows the time since the user has joined */
	public function timeSinceJoin($user = null){
		if($user == null){
			$user = $this->user;
		}
		$created 	= $this->getUser("created");
		$timeFirst	= strtotime($created);
		$timeSecond = strtotime("now");
		$memsince 	= $timeSecond - strtotime($created);
		$regged 		= date("n/j/Y", strtotime($created));
		
		if($memsince < 60) {
			$memfor = $memsince . "Seconds";
		}else if($memsince < 3600 && $memsince > 60){
			$memfor = floor($memsince / 60) . " Minutes";
		}else if($memsince < 86400 && $memsince > 60){
			$memfor = floor($memsince / 3600) . " Hours";
		}else if($memsince < 604800 && $memsince > 3600){
			$memfor = floor($memsince / 86400) . " Days";
		}else if($memsince < 2592000 && $memsince > 86400){
			$memfor = floor($memsince / 604800) . " Weeks";
		}else if($memsince > 604800){
			$memfor = floor($memsince / 2592000) . " Months";
		}
		return (string) $memfor;
	}
	
	/* Extra Tools/Functions */
	
	/* Check if valid E-Mail */
	public function validEmail($email = ""){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	
	/* Get the current page URL */
	public function curPageURL() {
		$pageURL = 'http';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){$pageURL .= "s";}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	/* Generate a Random String */
	public function rand_string($length) {
		$str="";
		$chars = "subinsblogabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen($chars);
		for($i = 0;$i < $length;$i++) {
			$str .= $chars[rand(0,$size-1)];
		}
		return $str;
	}
	
	/* Get the current page path */
	public function curPage(){
		$parts = parse_url($this->curPageURL());
		return $parts["path"];
	}
	
	/* Do a redirect */
	public function redirect($url, $status=302){
		header("Location: $url", true, $status);
	}
	
	/* Any mails need to be snt by logSys goes to here. */
	public function sendMail($email, $subject, $body){
		Open::sendEMail($email, $subject, $body);
	}
	/* End Extra Tools/Functions */
}
