<?
if(!defined("HOST")){
	include_once "../config.php";
}
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1, minimum-scale=1, width=device-width">
		<title>ERROR 404 - Not Found !!</title>
		<style>
			*{
			 margin:0;
			 padding:0;
			}
			html,code{
			 font:15px/22px arial,sans-serif;
			}
			html{
			 background:#fff;
			 color:#222;
			 padding:15px;
			}
			body{
			 margin:7% auto 0;
			 max-width:390px;
			 min-height:180px;
			 padding:10px 0 15px;
			}
			* > body{
			 background:url(<?echo HOST;?>/source/cdn/img/404.png) 80% 5px no-repeat;
			 padding-right:205px;
			}
			p{
			 margin:11px 0 22px;
			 overflow:hidden;
			}
			ins{
			 color:#777;
			 text-decoration:none;
			}
			a img{border:0}
			@media screen and (max-width:772px){
				body{
				 background:none;
				 margin-top:0;
				 max-width:none;
				 padding-right:0;
				}
			}
			a[onclick]{cursor:pointer;color:#0000EE;text-decoration: underline;}
		</style>
	</head>
	<body>
		<a href="<?echo HOST;?>/">
		<img src="<?echo HOST;?>/source/cdn/img/logo.png" alt="Open"></a>
		<p>
	 		<ins>The page doesn't exist or <br/>we don't like to show it to you.</ins>
		</p>
		<p>
		 The requested URL <b><?echo$_SERVER['REQUEST_URI'];?></b> was not found on this server.  <ins>Maybe you have clicked a wrong link.</ins>
		</p>
		<a onclick="window.history.go(-1)">Return to previous page</a>, <a  onclick="window.location='http://'+window.location.host">Home Page</a>
	</body>
</html>