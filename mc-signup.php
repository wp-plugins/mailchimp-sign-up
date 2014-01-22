<?php 
	/**
 * Plugin Name: MailChimp Sign Up
 * Plugin URI: http://jasonprescher.com/
 * Description: MailChimp signup form. Barebones ajax email input. | <a href="/wp-admin/options-general.php?page=mail-chimp-setup"> Settings &rsaquo;</a> 
 * Version: 0.1
 * Author: <a href="http://jasonprescher.com">Jason Prescher</a>
 * Author URI: http://jasonprescher.com/
 * License: A "Slug" license name e.g. GPL2
 *///use shortcode [mcsignup] to display signup box.
 /*function mc_func(){
 return '<div id="emailSignupForm"></div><div id="result"></div>';
	
	}*/
	$purl = plugins_url();	
	require ('includes/api.php'); 
/* Genrate form */		
	function mc_func(){ 	
		global $class;
		global $btnTxt;
		global $sTxt;
		global $purl;
		if($btnTxt == ''){$btnTxt = 'Go';}
		if($class == ''){$class = '';}
		if($sTxt == ''){$sTxt = 'Enter your email...';}
		return '<form class="'.$class.'" action="'.$purl.'/mailchimp-signup/includes/subscribe.php" id="invite" method="POST">
		<input type="text" placeholder="'.$sTxt.'" name="email" id="address" data-validate="validate(required, email)"/>
		<input type="submit" value="'.$btnTxt.'">
		</form><div id="result"></div>';
		};		
	add_shortcode( 'mcsignup', 'mc_func' );
/* Wordpress enqueue scripts */	
	wp_enqueue_script("jquery");
	global $purl;   
	$handle ='mc_email';
	$src = $purl.'/mailchimp-signup/assets/js/mc_subscribe.js';
	$deps = array("jquery");
	wp_enqueue_script( $handle, $src, $deps);
/* Wordpress admin menu */	
	function setup_theme_admin_menus() {
		add_submenu_page('options-general.php','Mail Chimp Setup', 'MailChimp', 'manage_options','mail-chimp-setup', 'mailchimp_settings');
	}

/* Plugin Settings */		
	function mailchimp_settings() {		
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		if($btnTxt == ''){$btnTxt = 'Go';}
		if($class == ''){$class = '';}
		if($sTxt == ''){$sTxt = 'Enter your email...';}
		global $purl; global $apikey; global $list; global $class; global $btnTxt; global $sTxt;
		if (isset($_POST["update_settings"])) {
			$apikey = esc_attr($_POST["key"]);
			$list = esc_attr($_POST["list"]);
			$class = esc_attr($_POST["class"]);
			$btnTxt = esc_attr($_POST["button"]);
			$sTxt = esc_attr($_POST["search"]);
			update_option("mail_chimp_key", $apikey );
			update_option("mail_chimp_list", $list );
			update_option("mail_chimp_class", $class );
			update_option("mail_chimp_button", $btnTxt );
			update_option("mail_chimp_search", $sTxt );
			$apikey = get_option("mail_chimp_key");
			$list = get_option("mail_chimp_list");
			$class = get_option("mail_chimp_class");
			$btnTxt = get_option("mail_chimp_button");
			$sTxt = get_option("mail_chimp_search");
			echo '<div id="message" class="updated" style="margin-top:25px;">Settings saved</div> ';

		}


		

?>
 <div style="margin:0px 0px 0px 10px;">
       <div style="margin:20px 0px 0px 0px;"><img src="<?php echo $purl.'/mailchimp-signup/assets/images/chimp.png'; ?>" /></div>
       <h2>MailChimp API Settings:</h2>
        <form method="POST" action=""> 
        <label for="key" style="display:block;  font-weight:900">API Key :</label> 
        <input type="text" name="key" value="<?php  global $apikey; echo $apikey ; ?>" size="36" /><br />
		<?php apiCheck();?>
	<hr  />
    <h3>Form Options</h3>
        <label for="class" style="display:block;  font-weight:900">CSS Class:</label>   
        <input type="text" name="class" value="<?php  echo $class ; ?>" /><br />
        <label for="button" style="display:block;  font-weight:900">Button Text:</label>   
        <input type="text" name="button" value="<?php  echo $btnTxt ; ?>" /><br />
        <label for="class" style="display:block;  font-weight:900">Place Holder:</label>   
        <input type="text" name="search" value="<?php  echo $sTxt ; ?>" /> 
        		<script>
		jQuery.noConflict();
		(function ($) {
			$(function () {
				$(document).ready(function () {
					var val = '<?php global $list; echo $list; ?>';
					$('input:checkbox[value="' + val + '"]').attr('checked', true);
					$("input[type='checkbox']").change(function () {
						$(this).closest(".checkboxContainer").find("input[type='checkbox']").not(this).prop("checked", false);
						$(this).prop("checked", true);
					});
				});
			});
		})(jQuery);</script>  

		<input type="hidden" name="update_settings" value="Y" /><br />
        <input type="submit" value="Save settings" class="button-primary"/>  
        </form>  
        <div style="margin-top:20px;">Use shortcode <span style="font-size:15px; background:#373737; padding:2px 5px; color:#fff; font-weight:900;">[mcsignup]</span> to display the form.</div>

        <div style="font-size:11px; margin-top:10px;"><p><img src="<?php  echo $purl.'/mailchimp-signup/assets/images/jp_logo_orange.png'; ?>" style="float:left"/> MailChimp sign up plugin by <a href="http://jasonprescher.com/" style="color:#D16249; text-decoration:none; font-style:italic">Jason Prescher</a></p></div>
        </div> 
        </div> 
		<?php } add_action("admin_menu", "setup_theme_admin_menus"); ?>