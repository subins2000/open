<?
class OpenPages extends Open{
 public $sourceDir;
 private $whichOne=false; /* Is the file existing with .php extension or by directly */
 
 public function __construct(){
  parent::__construct();
  $this->sourceDir=$this->root."source";
 }

  /* Does the requested file exists ? */
 public function exists($file){
  if(file_exists($this->sourceDir."/".$file.".php")){
   $this->whichOne=0;
  }
  if(file_exists($this->sourceDir."/".$file)){
   $this->whichOne=1;
  }
  return $this->whichOne===false ? false:true;
 }
 
 /* Function to include pages from source dir */
 public function incPage($file){
  if($this->exists($file)){
   $this->inc($this->whichOne==0 ? "source/".$file.".php":"source/".$file);
  }
 }
 
 /* Change the PHP_SELF value */
 private function changeServerValue($to){
  /* Only PHP_SELF is fake. Others are the original values */
  /* Change value to fake one (2 May) */
  $_SERVER['PHP_SELF']=$to;
 }
 
 /* Make the Profile Page */
 public function makeProfilePage($uri1, $uri2=""){
  /* Does the URI is like a profile page request ? */
  if((preg_match("/[0-9]/", $uri1) || $uri1=="profile") && ($uri2=="about" || $uri2=="reputation" || $uri2=="feed" || $uri2=="")){
   /* We make the ID of user for viewing the profile */
   $_GET['id']=$uri1=="profile" ? "" : $uri1;
   if($uri2!=""){
    $_GET['part']=$uri2;
    /* Profile page of requested ID with default tab as given */
    $this->incPage("profile.php");
   }elseif($uri2==""){
    /* Profile page of current logged in user */
    $this->incPage("profile.php");
   }else{
    /* Not valid profile request. So 404 */
    $this->ser();
   }
   return true;
  }else{
   return false; /* Not a profile page request */
  }
 }
 
 /* Make the View Post page */
 public function makeViewPage($uri1, $uri2=""){
  if($uri1=="view"){
   if($uri2!=""){
    /* Give the post ID from /view/215 where 215 is the post ID */
    $_GET['id']=$uri2;
   }
   $this->changeServerValue("/view.php");
   $this->incPage("view.php");
   return true;
  }else{
   return false;
  }
 }
 
 /* Make the messages page */
 public function makeChatPage($uri1, $uri2=""){
  if($uri1=="chat"){
   if($uri2!=""){
    /* Give the user ID from /chat/215 where 215 is the user ID */
    $_GET['id']=$uri2;
   }
   $this->changeServerValue("/chat.php");
   $this->incPage("chat.php");
   return true;
  }else{
   return false;
  }
 }
 
 /* Make the Find People page */
 public function makeFindPage($uri1, $uri2=""){
  if($uri1=="find"){
   if($uri2!=""){
    $_GET['q']=$uri2;
   }
   $this->changeServerValue("/find.php");
   $this->incPage("find.php");
   return true;
  }else{
   return false;
  }
 }
 
 /* Make the Data page to view user datas (images, uploads) */
 public function makeDataPage($uri1, $uriFull){
  if($uri1=="data"){
   $this->incPage("data/handle.php");
   return true;
  }else{
   return false;
  }
 }
 
 /* Make Redirects on some URLs to another. 302 is enough */
 public function makeRedirects($uri1, $uri2="", $uriFull=""){
  /* On all cdn URLs */
  if($uri1=="cdn"){
   if($uri2=="img"){
    $this->redirect($this->host."/source/".$uriFull, 302);
   }
  }
 }
}
?>