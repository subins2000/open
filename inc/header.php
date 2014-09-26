<header>
	<div class="logo">
		<a href='<?php echo HOST;?>' style='color:white;'>
			<h1 style="float:left;margin: 8px 0px 0px;">&nbsp;&nbsp;Open</h1>
		</a>
	</div>
	<nav>
		<?php if(loggedIn){?>
			<a href="<?php echo HOST;?>/home" class="button b-white home lNav" title="Home">Home</a>
			<a href="<?php echo HOST;?>/search" class="button b-white search lNav" title="Search">Search</a>
			<a href="<?php echo HOST;?>/find" class="button b-white find lNav" title="Find">Find</a>
			<a href="<?php echo HOST;?>/chat" class="button b-white chat lNav" title="Chat">Chat</a>
		<?php }else{?>
			<a href="<?php echo HOST;?>/login<?php
				if( isset($_SERVER['REQUEST_URI']) ){ 
					if( $_SERVER['REQUEST_URI'] != "/" && !preg_match("/\/login/", $_SERVER['REQUEST_URI']) ){
						echo "?c=";
						echo $_SERVER['REQUEST_URI'];
					}
				}
				?>" class="button b-red">Log In</a>
			<a href="<?php echo HOST;?>/register" class="button b-blue">Register</a>
		<?php }?>
	</nav>
	<?php if(loggedIn){ ?>
		<div class="curuserinfo">
			<button id="name_button" class="b-white" who="<?php echo $who;?>"><?php echo $uname;?></button>
			<div id="short_profile" class="c_c">
				<div class="left">
					<a href="<?php echo get('plink');?>">
						<b><?php
							/* Show the first name only */
							echo get("fname");
						?></b>
					</a>
					<div style="margin-top:15px;font-size:17px;font-weight:bold;" title="Reputation">
						<?php
						if(!class_exists("ORep")){
							require_once "$docRoot/inc/class.rep.php";
						}
						$HRep = new ORep();
						$HRep = $HRep->getRep($who);
						echo $HRep['total'];
						?>
					</div>
				</div>
				<div class="right">
					<a id="change_picture">Change Picture</a>
					<img src="<?php echo $uimg;?>" height="100" width="100"/>
				</div>
				<div class="bottom">
					<a href="<?php echo HOST;?>/me" class="button" style="position:absolute;left: 10px;top:3px;">Account</a>
					<a href="<?php echo HOST;?>/login?logout=true" class="button b-red" style="position:absolute;right: 10px;top:3px;">Log Out</a>
				</div>
			</div>
		</div>
		<div class="notifications">
			<?php
			$sql = $OP->dbh->prepare("SELECT COUNT(`red`) FROM `notify` WHERE `red`='0' AND `uid`=?");
			$sql->execute(array($who));
			$count = $sql->fetchColumn();
			$count = $count == "" ? 0 : $count;
			?>
			<a id="nfn_button" class="button b-white <?php echo $count == 0 ? "" : "b-red";?>"><?php echo $count;?></a>
				<div id="nfn" class="c_c">
					<center class="loading"><br/><br/><img src="<?php echo HOST. "/cdn/img/load.gif";?>"/><br/>Loading</center>
					<div class="nfs"></div>
				</div>
		</div>
	<?php } ?>
</header>