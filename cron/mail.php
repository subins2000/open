<?
/* Just a test file for testing whether mailing works */
ini_set("display_errors","on");
include("../inc/config.php");
send_mail("subins2000@gmail.com", "test", "Test");
?>