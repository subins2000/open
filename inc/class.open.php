<?php
class Open{
	
	public $uid, $root, $dbh, $host;
	public $mUsers = array(); // A temporary array for storing mentioned users
	
	private $secureKey = 'cantMakePublic';
	private $cache = array();
 
	public function __construct(){
		/* The Document Root */
		$this->root = docRoot;
		
		/* We make the Database configuration into an array */
		$database = unserialize(DATABASE);
		/* We make the PDO object */
		$this->dbh = new PDO("mysql:dbname={$database["name"]};host={$database["host"]};port={$database["port"]}", $database["user"], $database["pass"]);
  
		/* The hostname */
		$this->host = "http://".$_SERVER['HTTP_HOST']; /* currently no SSL for Open */

		return true;
	}
 
	/* Make the <head> tag. All params are not required */
	/* Accepts title, js & css files that are separated by a comma (,) */
	public function head($title="", $js="", $css=""){
		if($title!=""){
			echo "<title>$title | Open - An Open Source Social Network</title>";
		}else{
			echo "<title>Open - An Open Source Social Network</title>";
		}
		/* Make the Favicon */
		echo "<link rel='icon' href='". HOST ."/cdn/img/favicon.ico' />";
		
		/* The 'ubuntu' font */
		echo "<link async='async' href='https://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>";
		
		/* The Open CSS files */
		$css = $css != "" ? "main,$css":"main";
		echo "<link async='async' type='text/css' rel='stylesheet' href='" . HOST . "/css/{$css}' />";
	
		/* The Open JS files */
		/* First, stats tracker */
		if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "/me/ResetPassword"){
			/* It's directly placed in a <script> tag */
			$statsJS = file_get_contents(docRoot . "/cdn/js/stats.js");
			echo "<script>". $statsJS ."</script>";
		}
			
		/* Then, jQuery*/
		echo "<script src='". HOST ."/js/jquery'></script>";
		
		/* Then the other JS files that are mostly coded in jQuery - Thank you John Resig */
		$js = $js == "" ? "main" : "main,$js";
		/* Some extra js files under some conditions */
		if(loggedIn){
			$js .= ",time";
		}
		
		echo "<script async='async' src='" . HOST . "/js/".$js."'></script>";
	}
 
	/* Encrypts a string that is decryptable */
	public function encrypt($value){
		if($value==''){
			return false;
		}
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->secureKey, $value, MCRYPT_MODE_ECB, $iv);
		return urlencode(trim(base64_encode($crypttext)));
	}
 
	/* Decrypt hashes encrypted with $OP->encrypt() */
	public function decrypt($value){
		$value = urldecode($value);
		if(!$value || $value==null || $value=='' || base64_encode(base64_decode($value)) != $value){
			return $value;
		}else{
			$crypttext	= base64_decode($value);
			$iv_size	= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
			$iv 		= mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$decryptSTR = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->secureKey, $crypttext, MCRYPT_MODE_ECB, $iv);
			return trim($decryptSTR);
		}
	}
 
	/* Generate a random string */
	public function randStr($length){
		$str	= "";
		$chars	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$size	= strlen($chars);
		for($i=0;$i<$length;$i++){
			$str .= $chars[rand(0, $size-1)];
		}
		return $str;
	}
 
	/* Get user options */
	public function get($key, $userID="", $j=true){
		if($userID == ""){
			$userID = curUser;
		}
		if(isset($this->cache['uData'][$userID])){
			$data=$this->cache['uData'][$userID];
		}else{
			$sql=$this->dbh->prepare("SELECT * FROM `users` WHERE `id`=:id");
			$sql->bindValue(":id", $userID, PDO::PARAM_INT);
			$sql->execute();
			if($sql->rowCount()!=0){
				$data = $sql->fetch(PDO::FETCH_ASSOC);
				$newJSON = json_decode($data['udata'], true);
				$newJSON = is_array($newJSON) ? $newJSON : array();
				$newJSON['ploc']  = HOST . "/" . $userID;
				$data['udata'] = json_encode($newJSON);
				$this->cache['uData'][$userID]=$data;
			}
		}
		if(isset($data)){
			if($key == 'img'){
				$data = json_decode($data['udata'], true);
				$data = isset($data["img"]) ? $this->format($data["img"]):"";
				$data = $data=='' ? HOST . "/cdn/img/avatars/om.png":$data;
				return $data;
			}elseif($key == 'plink'){
				return HOST."/$userID";
			}elseif($key == "status"){
				$data = $data['seen'];
				if($data < date("Y-m-d H:i:s",strtotime('-20 seconds', time()))){
					return "off";
				}else{
					return "on";
				}
			}elseif($key == "avatar"){
				$img = get("img", $userID);
				if(preg_match("/avatars\/om/", $img) || $img==""){
					$img = HOST . "/cdn/img/avatars/om.png";
				}elseif(!preg_match("/imgur/", $img) && !preg_match("/akamaihd/", $img) && !preg_match("/google/", $img) && $img!=""){
					$img = "$img/small.png";
				}
				return $img;
			}elseif($key == "fname"){
				$data = $this->format($data["name"]);
				$data = explode(" ", $data);
				return $data[0];
			}elseif($j === true){
				$data = json_decode($data['udata'], true);
				if(isset($data[$key])){
					$data = is_array($data[$key]) ? $data[$key] : $this->format($data[$key]);
				}else{
					$data = "";
				}
				return $data;
			}else{
				return isset($data[$key]) ? $this->format($data[$key]):"";
			}
		}
	}
 
	/* Function to save data of current user */
	public function save($key, $val=""){
		$sql = $this->dbh->prepare("SELECT `udata` FROM `users` WHERE `id`=?");
		$sql->execute(array(curUser));
		$data = $sql->fetch();
		if($key == "seen"){
			$val = date("Y-m-d H:i:s", time());
			$sql = $this->dbh->prepare("UPDATE `users` SET `seen`=? WHERE `id`=?");
			$sql->execute(array($val, curUser));
		}else{
			$arr = json_decode($data['udata'], true);
			$arr[$key] = $val;
			$sql = $this->dbh->prepare("UPDATE `users` SET `udata`=? WHERE `id`=?");
			$sql->execute(array(json_encode($arr), curUser));
		}
	}
 
	/* A redirect function that support HTTP status code for redirection 
	* 302 - Moved Temporarily
	*/
	public function redirect($url, $status = 302){
  		$url = self::URL($url); // Make a valid, good URL
  		header("Location: $url", true, $status);
  		exit;
	}
 
	/* Function to show error messages. If no values are given, error 400 is shown */
	public function ser($title = "", $description = "", $type = "html", $exit = true){
		if($type == "html"){
			if($title == ''){
				header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
				include "{$this->root}/inc/errorPage.php";
			}else{
				$html = "<h2 style='color:red;font-family:ubuntu;'>$title</h2>";
				if($description!=''){
					$html.="<span style='color:red;font-family:ubuntu;'>$description</span>";
				}
				echo $html;
			}
		}elseif($type=="json"){
			header('Content-type: text/html');
			$title = $title == "" ? "There was an error, Check If something is wrong." : $title;
			$arr = array(
				"error" => 1,
				"msg"	=> $title
			);
			echo json_encode($arr);
		}
		if ($exit){ exit; } /* We don't want codes after it to run */
	}
 
	public function sss($title, $description){ /* To show success messages */
		if($title==''){
			$html="<h2 style='color:green;font-family:ubuntu;'>Operation Success</h2>";
		}else{
			$html="<h2 style='color:green;font-family:ubuntu;'>$title</h2>";
		}
		if($description!=''){
			$html.="<span style='color:green;font-family:ubuntu;'>$description</span>";
		}
		echo $html;
		/* No exit here because it's a success message */
	}
 
	/* --------------------
	 * Formatting Functions
	 * --------------------
	*/
 
	/* For making clean URLs */
	private function goodURL($matches){
		$ots = "";
		$url = str_replace("\n", "", str_replace("\s", "", $matches[0]));
		if($matches[2]=='http://' || $matches[2]=='https://' || $matches[2]=='www'){
			$t = $matches[1];
		}else{
			$t = $matches[2];
		}
		if(preg_match("/\n/", $t)){
			$t	 = str_replace("\n", "", $t);
			$ots = "\n";
		}
		if($matches[2] == 'www'){
			$url = "http://$u";
		}
		return '<a href="'.$url.'">'.$t.'</a>'.$ots;
	}
 
	/* Makes @1 into <a href='//open.subinsb.com/1'>@SubinSiby</a> */
	public function smention($s, $t){
		$userid=$t[1];
		$nxs=strpos($s, "@$userid");
		$nxs=strlen("@$userid") + $nxs;
		$nxs=substr($s, $nxs, 1);
		$sql = $this->dbh->prepare("SELECT `name` FROM `users` WHERE `id`=?");
		$sql->execute(array($userid));
		if($sql->rowCount()==0){
			return "@$userid{$nxs}";
		}else{
			$name = $sql->fetchColumn();
			$html = "<a href='". HOST ."/$userid'>@$name</a>{$nxs}";
			$this->mUsers[$userid] = 1;
			return $html;
		}
	}
 
	/* Make notifications for mentioned users */
	public function mentionNotify($pid, $type="post"){
		/* Short for mentioned users - mUsers */
		$mUsers = $this->mUsers;
		if(count($mUsers)!=0){  
			foreach($mUsers as $userId=>$notNeeded){
				if($userId != curUser){
					$this->notify("mention", $type, $pid, $userId, curUser);
				}
			}
		}
	}
 
	/* A formatting function to filter from HTML string (prevent XSS) and also advanced filtering (@mentions etc..) */
	public function format($string, $advanced = false){
		$string = htmlspecialchars($string);
		if($advanced === true){
			$string = preg_replace("/\*\*(.*?)\*\*/", '<b>$1</b>', $string);
			$string = preg_replace("/\*\/(.*?)\/\*/", '<i>$1</i>', $string);
			$string = preg_replace_callback('@((www|http://|https://)(.*?)(\s|\z|\n)+)@', array($this, 'goodURL'), $string);
			$string = preg_replace('@(\#[^ |\@|\&|\*]+)@', '<a href="http://open.subinsb.com/search/\1">\1</a>', $string);
			$string = str_replace("http://open.subinsb.com/search/#", Open::URL("search/%23"), $string);
			
			$that   = $this; // Because in callback functions, $this cannot defined directly and cannot be used
			$string = preg_replace_callback("/\@(.*?)(\s|\z|[^0-9])/", function($text) use ($string, $that){
				return $that->smention($string, $text);
			}, $string);
		}
		return $string;
	}
 
	/* Converts long multiple lines string in to a single line string. Short for Rendering Filter */
	/* Very useful for including HTML as values in JavaScript variables */
	public function rendFilt($string){
		$newString = str_replace("\r", "", $string);
		$newString = str_replace('"', "'", $newString);
		$newString = str_replace("/",'"+"/"+"', $newString);
		$newString = str_replace("\n", "<br/>", $newString);
		return $newString;
	}
	
	public function reverseMarkup($s){
		$s = preg_replace("/\*\*(.*?)\*\*/",'$1', $s);
		$s = preg_replace("/\*\/(.*?)\/\*/",'$1', $s);
		return $s;
	}
 
	/* --------------------
	 * End Fomratting Functions 
	 * --------------------
	*/
	/* --------------------
	 * Home, Feed Functions 
	 * --------------------
	*/
 
	/* Get all the likes of the current logged in user */
	private function getLikes($type="post"){
		$likes 	= array();
		$cached = false;
		if( isset($this->cache["all{$type}Likes"]) ){
			$likes = $this->cache["all{$type}Likes"];
			$cached = true;
		}else{
			if( $type == "post"){
				$sql=$this->dbh->prepare("SELECT `pid` FROM `likes` WHERE uid=?");
				$sql->execute(array(curUser));
				while($r = $sql->fetch()){
					$likes[]=$r['pid'];
				}
			}elseif( $type == "cmt" ){
				$sql=$this->dbh->prepare("SELECT `cid` FROM `commentLikes` WHERE uid=?");
				$sql->execute(array(curUser));
				while($r = $sql->fetch()){
					$likes[]=$r['cid'];
				}
			}
			/* Cache the likes for later use */
			$this->cache["all{$type}Likes"] = $likes;
		}
		return $likes;
	}
 
	/* Give the post/comment ID and a boolean whether if the user liked it or not is given */
	public function didLike($id, $type){
		if($type == "post"){
			$all = $this->getLikes("post");
		}else{
			$all = $this->getLikes("cmt");
		}
		return array_search($id, $all)===false ? false:true;
	}
 
	/* Prints the HTML of Follow button when the User id is given */
	public function followButton($uid){
		if($uid == curUser || !loggedIn){
			return false;
		}else{
			$sql = $this->dbh->prepare("SELECT `uid` FROM `conn` WHERE `fid`=? and `uid`=?");
			$sql->execute(array($uid, curUser));
			if($sql->rowCount()==0){
				return "<a id='{$uid}' class='button follow'><span>+</span><span class='text'>Follow</span></a>";
			}else{
				return "<a id='{$uid}' class='button unfollow'><span>-</span><span class='text'>Unfollow</span></a>";
			}
		}
	}
 
	/* --------------------
	 * End Feed/Home 
	 * --------------------
	*/
	/* --------------------
	 * Other functions 
	 * --------------------
	*/
	
	/*
	 * Notify a user. Add a notification to a user's notification inbox
	$action - Action
	$text	- Message
	$pid    - Post ID
	$to     - The User Id of the one who did the action to.
	$from	- Current User ID. The one who made this notification
	*/
	
	public function notify($action, $text, $pid, $to, $from){
  		global $docRoot;
		$dontSend	= 0; // 0 for send email, 1 for don't send email
		$n			= get("name", $from, false); // Full name
		$sn			= explode(" ", $n);
		$sn			= $sn[0]; // First name
		$mail		= get("username", $to, false); // The mail address is the username
		if($to == curUser){
   			$dontSend = 1;
		}
		if($action == "comment" && $to != curUser){
   			$lci = $this->dbh->prepare("SELECT `id` FROM `cmt` WHERE `pid`=? AND `uid`=? ORDER BY `id` DESC LIMIT 1");
   			$lci->execute(array($pid, curUser));
   			$lci = $lci->fetchColumn(); // Last comment ID
   			
   			/* Add notification */
   			$sql = $this->dbh->prepare("INSERT INTO `notify` (`uid`, `fid`, `ty`, `post`, `posted`) VALUES (?, ?, ?, ?, NOW())");
   			$sql->execute(array($to, curUser, "cmt", "$lci-$pid"));
   			
   			/* Get comments count of post */
   			$sql = $this->dbh->prepare("SELECT 1 FROM `posts` WHERE `id` = ?");
   			$sql->execute(array($pid));
   			$cCmts = $sql->rowCount();
   			
   			/* Make the email */
   			$body  = "{$n} commented on your <a href='". HOST ."/view/$pid'>post</a> :";
   			$body .= "<blockquote>$text</blockquote>";
   			$body .= "Your post now have <b>{$cCmts}</b> comments.<br/><a href='". HOST ."/view/$pid#$lci' target='_blank'>";
				$body .= "<button style='padding:5px 15px;'>View Post</button>";
			$body .= "</a>";
   			$title = "$sn Commented On Your Post";// The email subject
  		}
  		if($action=="follow" && $to != curUser){
   			$sql = $this->dbh->prepare("SELECT 1 FROM `notify` WHERE `uid` = ? AND `fid` = ? AND `ty` = ?");
   			$sql->execute(array($to, curUser, "fol"));
   			
   			if($sql->fetchColumn() == 0){
    			/* Add notification */
    			$sql = $this->dbh->prepare("INSERT INTO `notify` (`uid`, `fid`, `ty`, `post`, `posted`) VALUES (?, ?, ?, ?, NOW())");
    			$sql->execute(array($to, curUser, "fol", ""));
    			
    			/* Get number of followers */
    			$sql = $this->dbh->prepare("SELECT `fid` FROM `conn` WHERE `fid` = ?");
    			$sql->execute(array($to));
    			$cFoll = $sql->rowCount();
    			
    			$body  = "{$n} is now Following You.";
    			$body .= "<div style='margin: 10px;'><img src='". get("img", $from) ."' style='display:inline-block;vertical-align:top;' height='120' width='120'/><div style='display:inline-block;vertical-align:top;width: 200px;margin-left:10px;'>{$sn} added you to his following list. You now have <b>{$cFoll}</b> followers. If you now follow this person back, you will become friends with {$sn}.</div></div>";
    			$body .= "<a href='" . HOST . "/$from' target='_blank'><button style='padding:5px 15px;'>See $sn's Profile</button></a>";
    			$title = "You Have a New Follower"; // E-Mail subject
   			}else{
    			$dontSend = 1;
			}
  		}
  		if($action == "mention" && $to != curUser){
   			/* Delete Existing This Action (mention) Notifications */
   			$sql = $this->dbh->prepare("DELETE FROM `notify` WHERE `uid` = ? AND `fid` = ? AND `ty` = ?");
   			$sql->execute(array($to, curUser, $text == "post" ? "men" : "menc"));
   			
   			/* and add new notification */
   			$sql = $this->dbh->prepare("INSERT INTO `notify` (`uid`, `fid`, `ty`, `post`, `posted`) VALUES (?, ?, ?, ?, NOW())");
   			if($text == "post"){
    			$sql->execute(array($to, curUser, "men", "0-$pid"));
   			}else{
    			$commentID = $this->dbh->prepare("SELECT `id` FROM `cmt` WHERE `pid`=? AND `uid`=? ORDER BY `id` DESC LIMIT 1");
    			$commentID->execute(array($pid, curUser));
    			$commentID = $commentID->fetchColumn();
    			$sql->execute(array($to, curUser, "menc", "$commentID-$pid"));
   			}
   			$body = "{$n} mentioned you in his {$text}. See the {$text} to read what {$sn} had said about you.<br/>";
   			if($text == "post"){
    			$body .= "<a href='". HOST ."/view/$pid' target='_blank'>";
   			}else{
				$body .= "<a href='". HOST ."/view/$pid#{$commentID}' target='_blank'>";
   			}
   			$body .= "<button style='padding:5px 15px;'>See {$sn}'s ". strtoupper($text) ."</button></a>&nbsp;&nbsp;&nbsp;";
   			$body .= "<a href='". get("plink", $from) ."' target='_blank'><button style='padding:5px 15px;'>See {$sn}'s Profile</button></a>";
   			$title = "{$sn} Mentioned You in His {$text}";
		}
  		if($action == "msg" && $to != curUser){
   			/* Get the posted time / creation time of the last message notification if there is one */
   			$sql = $this->dbh->prepare("SELECT `posted` FROM `notify` WHERE `uid` = ? AND `fid` = ? AND `ty` = ? ORDER BY `id` DESC LIMIT 1");
   			$sql->execute(array($to, curUser, "msg"));
   			$lps = $sql->fetchColumn();
   			
   			date_default_timezone_set("GMT");
   			if(strtotime($lps) < strtotime("-20 minutes") || $lps==""){
    			$sql = $this->dbh->prepare("INSERT INTO `notify` (`uid`, `fid`, `ty`, `post`, `posted`) VALUES (?, ?, ?, ?, NOW())");
    			$sql->execute(array($to, curUser, "msg", ""));
    			$body  = "{$n} sent you a message :";
    			$body .= "<blockquote>{$text}</blockquote>";
    			$body .= "See the messages page to see other messages sent by {$sn}.";
    			$body .= "<a href='" . HOST . "/chat/$from'><button style='padding:5px 15px;'>See {$sn}'s Messages</button></a>&nbsp;&nbsp;&nbsp;";
    			$body .= "<a href='" . HOST . "/$from'><button style='padding:5px 15px;'>See {$sn}'s Profile</button></a>";
    			$title = "{$sn} Sent you a message";   
   			}else{
    			$dontSend = 1;
   			}
		}
		$settings	= get("NfS", $to); // get the type of notifications the user doesn't like to emailed
  		$action		= str_replace("mention", "men", 
			str_replace("follow", "fol", 
				str_replace("comment", "cmt", $action)
			)
		); // Make the $action to a text that is valid to the "NfS" setting. In "NfS" setting, "follow" is identified as "fol"
		if( is_array($settings) && isset($settings[$action]) ){
   			$dontSend = 1;
		}
		if($dontSend == 0){
   			/* $OP->sendEMail($mail, $title, $m); -- Not Needed Anymore */
   			$sql = $this->dbh->prepare("INSERT INTO `mails` (`email`, `sub`, `message`) VALUES (?, ?, ?)");
			$sql->execute(array($mail, $title, $body));
		}
	}
 
	/* For sending mails */
 
	/* 5 accounts are currently used for sending mails
	* by shift. If one account is used before,
	* the next account is used now.
	* One Mailgun account and 4 Microsoft Outlook acccounts
	*/
	public static function sendEMail($mail, $subject, $content){
		/* Prepare the HTML message */
		$html = '<div style="width:100%;margin:0px;background:#EEE;background:-webkit-linear-gradient(#CCC,#EEE);background:-moz-linear-gradient(#CCC,#EEE);padding:2px;height:100px;"><h1><a href="http://open.subinsb.com"><img style="margin-left:40px;float:left;" src="http://open.subinsb.com/cdn/img/logo.png"></a></h1><div style="float:right;margin-right:40px;font-size:20px;margin-top:20px"><a href="http://open.subinsb.com/me">Manage Account</a>&nbsp;&nbsp;&nbsp;<a href="http://open.subinsb.com/me/ResetPassword">Forgot password ?</a></div></div><h2>'.$subject.'</h2><div style="margin-left: 10px;padding: 5px 10px;margin-right:10px">'.$content.'</div><p>Report Bugs, Problems, Suggestions & Feedback @ <a href="https://github.com/subins2000/open/issues">GitHub</a> Or Send Feedback Via HashTag <a href="http://open.subinsb.com/search?q=%23feedback">feedback</a><br/><a href="http://open.subinsb.com/me/Notify">Manage Mail Notifications</a></p>';
  
		/* Append Company name to the subject */
		$subject .= " - Open";
  
		/* We keep a status file for knowing which Mail Account was used for sending the previous email */
		$statusFile = docRoot . "/inc/nextMailAccount.txt";
		
		/* $nowAccount means that the account that should be currently used to send the email */
		$nowAccount = file_get_contents($statusFile);
  
		if($nowAccount == "5" || $nowAccount == "0"){
			/* This is the Mailgun account */
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, 'api:key-6qtg93oyx-ffc5aseuumo8-sn1x3jov2');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/open.subinsb.com/messages');
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					"from"    => 'Open <noreply@open.subinsb.com>',
					"to"      => $mail,
					"subject" => $subject,
					"html"    => $html
				)
			);
			$result = curl_exec($ch);
			curl_close($ch);
			$newStatus = $nowAccount == "5" ? 0 : $nowAccount + 1;
		}else{
			require_once docRoot . "/inc/mailer/class.phpmailer.php";
			$mailAccounts = array(
				1 => array("subinsiby1@gmail.com", "password"),
				2 => array("friendshoodhelp@gmail.com", 'password'),
				3 => array("openautomail@gmail.com", "password"),
				4 => array("200ccl4@gmail.com", "password")
			);
			try {
				$mailUser  	= $mailAccounts[$nowAccount][0];
				$mailPass  	= $mailAccounts[$nowAccount][1];
				$phpMailer 	= new PHPMailer();
				$phpMailer->IsSMTP();
				$phpMailer->CharSet     = 'UTF-8';
				$phpMailer->SMTPAuth    = true;
				$phpMailer->SMTPSecure  = "ssl";
				$phpMailer->Host        = "smtp.gmail.com";
				$phpMailer->Port        = 465;
				$phpMailer->Username    = $mailUser;
				$phpMailer->Password    = $mailPass;
				$phpMailer->From        = 'noreply@open.subinsb.com';
				$phpMailer->FromName    = 'Open Mail';
				$phpMailer->isHTML(true);
				$phpMailer->Subject     = $subject;
				$phpMailer->SMTPDebug   = false;
				$phpMailer->Debugoutput = 'echo';
				$phpMailer->Body        = $html;
				$phpMailer->addAddress($mail);
				$result	= $phpMailer->send();
			}catch (Exception $e) {
				$result = false;
			}
			
			/* $lastAccount+1 - the next account that should be used */
			$newStatus = $nowAccount + 1;
		}
  
		/* We replace with the new status (the last account used) */
		if(isset($newStatus)){
			file_put_contents($statusFile, $newStatus);
		}
		return $result;
	}
	
	/* --------------
	 * HTML Functions
	 * --------------
	*/
	
	/* Generate valid URLS. eg: if `$path` is /me/good it will be made into http://open.subinsb.com/me/good */
	public static function URL($path){
		$path = substr($path, 0, 1) == "/" ? substr($path, 1) : $path;
		$url  = $path;
		if($path == ""){
			$url = HOST;
		}elseif( !preg_match("/http/", $path) || !preg_match("/".CLEAN_HOST."/", $path) ){
			$url = HOST . "/$path";
		}
		return $url;
	}
	
	/* --------------
	 * Extra Functions
	 * --------------
	*/
	/* These functions are not used globally, but on certain files */
	
	/* For encoding the search query */
	public static function encodeQuery($query, $decode = false){
		if( $decode ){
			$new = str_replace("/", "%2F", $query);
			$new = rawurldecode($new);
			$new = htmlspecialchars_decode($new);
		}else{
			$new = htmlspecialchars($query);
			$new = rawurlencode($new);
			$new = str_replace("%2F", "/", $new);
		}
		return $new;
	}
}
?>