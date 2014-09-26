<?php
$LS->init();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php $OP->head("Linked Acounts - Manage Account");?>
	</head>
	<body>
		<?php
		include "$docRoot/inc/header.php";
		if(isset($_POST['submit'])){
			$shouldNotSend	= array();
			$optSend		= array("cmt", "fol", "msg", "men");
			foreach($optSend as $v){
				if(!isset($_POST[$v])){
					$shouldNotSend[$v] = "";
				}
			}
			$OP->save("NfS", $shouldNotSend);
		}
		$nfs = isset($shouldNotSend) ? $shouldNotSend : $OP->get("NfS", $who);
		function checkOrNot($t, $nfs){
			echo "name='$t' ";
			if( !isset($nfs[$t]) ){
				echo "checked='checked'";
			}
		}
		?>
		<div class="wrapper">
			<div class="content">
				<h1>Notification Settings</h1>
				<p>What kind of notifications should we send you by E-Mail ?</p><cl/>
				<form method="POST">
					<table cellspacing="15">
						<tbody>
							<tr>
								<td>Type Of Notification</td>
								<td>Should We Send It ?</td>
							</tr>
							<tr>
								<td>When Someone Comments On Your Post</td>
								<td><input type="checkbox" <?php checkOrNot("cmt", $nfs);?>/></td>
							</tr>
							<tr>
								<td>When Someone Follows You</td>
								<td><input type="checkbox" <?php checkOrNot("fol", $nfs);?>/></td>
							</tr>
							<tr>
								<td>When Someone Messages You</td>
								<td><input type="checkbox" <?php checkOrNot("msg", $nfs);?>/></td>
							</tr>
							<tr>
								<td>When Someone Mentions You in Posts/Comments</td>
								<td><input type="checkbox" <?php checkOrNot("men", $nfs);?>/></td>
							</tr>
						</tbody>
					</table>
					<input name='submit' type="submit" value="Update Settings"/>
				</form>
			</div>
		</div>
	</body>
</html>