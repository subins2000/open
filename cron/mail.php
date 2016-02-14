<?php
/* Just a test file for testing whether mailing works */
ini_set("display_errors", "on");
include("../load.php");
$OP->sendEMail("subins2000@gmail.com", "test", "Test");
?>