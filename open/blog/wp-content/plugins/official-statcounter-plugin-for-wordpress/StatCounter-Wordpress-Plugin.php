<?php
/*
 * Plugin Name: Official StatCounter Plugin
 * Version: 1.6.5
 * Plugin URI: http://statcounter.com/
 * Description: Adds the StatCounter tracking code to your blog. <br>To get setup: 1) Activate this plugin  2) Enter your StatCounter Project ID and Security Code in the <a href="options-general.php?page=StatCounter-Wordpress-Plugin.php"><strong>options page</strong></a>.
 * Author: Aodhan Cullen
 * Author URI: http://statcounter.com/
 */

// Defaults, etc.
define("key_sc_project", "sc_project", true);
define("key_sc_position", "sc_position", true);
// legacy problem with sc_security naming
define("key_sc_security", "key_sc_security", true);



define("sc_project_default", "0" , true);
define("sc_security_default", "" , true);
define("sc_position_default", "footer", true);

// Create the default key and status
add_option(key_sc_project, sc_project_default, 'Your StatCounter Project ID.');
add_option(key_sc_security, sc_security_default, 'Your StatCounter Security String.');
add_option("sc_invisible", "0", 'Force invisibility.');

// Create a option page for settings
add_action('admin_menu' , 'add_sc_option_page' );
add_action( 'admin_menu', 'statcounter_admin_menu' );

function statcounter_admin_menu() {
	$hook = add_submenu_page('index.php', __('StatCounter Stats'), __('StatCounter Stats'), 'publish_posts', 'statcounter', 'statcounter_reports_page');
	add_action("load-$hook", 'statcounter_reports_load');
	$hook = add_submenu_page('plugins.php', __('StatCounter Admin'), __('StatCounter Admin'), 'manage_options', 'statcounter_admin', 'sc_options_page');
}

function statcounter_reports_load() {
	add_action('admin_head', 'statcounter_reports_head');
}

function statcounter_reports_head() {
?>
<style type="text/css">
	body { height: 100%; }
</style>
<?php
}

function statcounter_reports_page() {
    $sc_project = get_option(key_sc_project);
    if($sc_project==0) {
			$sc_link = 'http://statcounter.com/';
    } else {
			$sc_link = 'http://statcounter.com/p'.$sc_project.'/?source=wordpress';
    }
    	
        echo '<iframe id="statcounter_frame" src="'.$sc_link.'" width="100%" height="2000">
<p>Your browser does not support iframes.</p>
</iframe>';

}



// Hook in the options page function
function add_sc_option_page() {
	global $wpdb;
	add_options_page('StatCounter Options', 'StatCounter', 8, basename(__FILE__), 'sc_options_page');
}

function sc_options_page() {
	// If we are a postback, store the options
 	if ( isset( $_POST['info_update'] ) ) {
		check_admin_referer();
		
		// Update the Project ID
		$sc_project = trim($_POST[key_sc_project]);
		if ($sc_project == '') {
			$sc_project = sc_project_default;
		}
		update_option(key_sc_project, $sc_project);

		// Update the Security ID
		$sc_security = trim($_POST[key_sc_security]);
		if ($sc_security =='') {
			$sc_security = sc_security_default;
		}
		update_option(key_sc_security, $sc_security);
		
		// Update the position
		$sc_position = $_POST[key_sc_position];
		if (($sc_position != 'header') && ($sc_position != 'footer')) {
			$sc_position = sc_position_default;
		}

		update_option(key_sc_position, $sc_position);
		
		// Force invisibility
		$sc_invisible = $_POST['sc_invisible'];
		if ($sc_invisible == 1) {
			update_option('sc_invisible', "1");		
		} else {
			update_option('sc_invisible', "0");				
		}

		// Give an updated message
		echo "<div class='updated'><p><strong>StatCounter options updated</strong></p></div>";
	}

	// Output the options page
	?>

		<form method="post" action="options-general.php?page=StatCounter-Wordpress-Plugin.php">
		<div class="wrap">
			<?php if (get_option( key_sc_project ) == "0") { ?>
				<div style="margin:10px auto; border:3px #f00 solid; background-color:#fdd; color:#000; padding:10px; text-align:center;">
				StatCounter Plugin has been activated, but will not be enabled until you enter your <strong>Project ID</strong> and <strong>Security Code</strong>.
				</div>
			<?php } ?>
			<h2>Using StatCounter</h2>
			<blockquote><a href="http://statcounter.com" style="font-weight:bold;">StatCounter</a> is a free web traffic analysis service, which provides summary stats on all your traffic and a detailed analysis of your last 500 page views. This limit can be increased by upgrading to a paid service.</p>
			<p>To activate the StatCounter service for your WordPress site:<ul>
				<li><a href="http://statcounter.com/sign-up/" style="font-weight:bold;">Sign Up</a> with StatCounter or <a href="http://statcounter.com/add-project/" style="font-weight:bold;">add a new project</a> to your existing account</li>
				<li>The installation process will detect your WordPress installation and provide you with your <strong>Project ID</strong> and <strong>Security Code</strong></li>
			</ul></blockquote>
			<h2>StatCounter Options</h2>
			<blockquote>
			<fieldset class='options'>
				<table class="editform" cellspacing="2" cellpadding="5">
					<tr>
						<td>
							<label for="<?php echo key_sc_project; ?>">Project ID:</label>
						</td>
						<td>
							<?php
							echo "<input type='text' size='11' ";
							echo "name='".key_sc_project."' ";
							echo "id='".key_sc_project."' ";
							echo "value='".get_option(key_sc_project)."' />\n";
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo key_sc_security; ?>">Security Code:</label>
						</td>
						<td>
							<?php
							echo "<input type='text' size='9' ";
							echo "name='".key_sc_security."' ";
							echo "id='".key_sc_security."' ";
							echo "value='".get_option(key_sc_security)."' />\n";
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="<?php echo key_sc_position; ?>">Counter Position:</label>
						</td>
						<td>
							<?php
							echo "<select name='".key_sc_position."' id='".key_sc_position."'>\n";
							
							echo "<option value='header'";
							if(get_option(key_sc_position) == "header")
								echo " selected='selected'";
							echo ">Header</option>\n";
							
							echo "<option value='footer'";
							if(get_option(key_sc_position) != "header")
								echo" selected='selected'";
							echo ">Footer</option>\n";
							
							echo "</select>\n";
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label for="sc_invisible">Force invisibility:</label>
						</td>
						<td>
							<?php
							$checked = "";
							if(get_option('sc_invisible')==1) {
								$checked = "checked";
							}
							echo "<input type='checkbox' name='sc_invisible' id='sc_invisible' value='1' ".$checked.">\n";
							?>
						</td>
					</tr>								
				</table>
			</fieldset>
			</blockquote>
						<p class="submit">
				<input type='submit' name='info_update' value='Update Options' />
			</p>
		</div>
		</form>

<?php
}

$sc_position = get_option(key_sc_position);
if ($sc_position=="header") {
	add_action('wp_head', 'add_statcounter');
} else {
	add_action('wp_footer', 'add_statcounter');
}



// The guts of the StatCounter script
function add_statcounter() {
	global $user_level;
	$sc_project = get_option(key_sc_project);
	$sc_security = get_option(key_sc_security);
	$sc_invisible = 0;
	$sc_invisible = get_option('sc_invisible');
	if (
		( $sc_project > 0 )
	 ) {
?>
	<!-- Start of StatCounter Code -->
	<script type="text/javascript">
	<!-- 
		var sc_project=<?php echo $sc_project; ?>; 
		var sc_security="<?php echo $sc_security; ?>"; 
<?php 
if($sc_invisible==1) {
	echo "		var sc_invisible=1;\n"; 
}?>
	//-->
	</script>
	<script type="text/javascript" src="http://www.statcounter.com/counter/counter_xhtml.js"></script>
<noscript><div class="statcounter"><a title="web analytics" href="http://statcounter.com/"><img class="statcounter" src="http://c.statcounter.com/<?php echo $sc_project; ?>/0/<?php echo $sc_security; ?>/<?php echo $sc_invisible; ?>/" alt="web analytics" /></a></div></noscript>	
	<!-- End of StatCounter Code -->
<?php
	}
}

?>
