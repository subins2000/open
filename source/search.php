<?php
$LS->init();
$_GET['q']	 = isset($_GET['q']) ? Open::encodeQuery($_GET['q'], true) : "";
$searchQuery = $OP->format( $_GET['q'] );
if($searchQuery != ''){
	$sql = $OP->dbh->prepare("UPDATE `trend` SET `hits` = `hits` + 1 WHERE `title` = ?");
	$sql->execute(array($_GET['q']));
	if($sql->rowCount() == 0){
		$sql = $OP->dbh->prepare("INSERT INTO `trend` (`title`, `hits`) VALUES(?, '1')");
		$sql->execute(array($_GET['q']));
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="type" value="search"></meta>
		<?php $OP->head("Search", "ac,post_form,home,time,gadget", "ac,home,post_form,gadget");?>
	</head>
	<body>
		<?php include "$docRoot/inc/header.php";?>
		<div class="content blocks">
			<div class="block left viewpoint">
				<form action="<?php echo Open::URL('search');?>" method="GET">
					<input type="text" class="block" value="<?php echo $searchQuery;?>" name="q" id="q" />
					<button class="block">Search</button>
				</form>
				<style>
				#q{
					width:375px;
					margin: 0px;
				}
				@media screen and (max-width:500px){
					#q{
						width:auto;
					}
				}
				</style>
				<?php
				include "$docRoot/inc/post_form.php";
				?>
				<div class="feed">
					<?php
					include "$docRoot/inc/feed.php";
					?>
				</div>
			</div>
			<div class="block right viewpoint">
				<?php
				include_once "$docRoot/inc/trending.php";
				?>
			</div>
		</div>
		<?php include "$docRoot/inc/gadget.php";?>
	</body>
</html>