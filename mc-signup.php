<?php 
	/**
 * Plugin Name: MailChimp Ajax Subscribe
 * Plugin URI: http://jasonprescher.com/
 * Description: MailChimp signup form. Simple Ajax email input. 
 * Version: 0.1.1
 * Author: <a href="http://jasonprescher.com">Jason Prescher</a>
 * Author URI: http://jasonprescher.com/
 * License: A "Slug" license name e.g. GPL2
 */
  
	require ('includes/api.php'); 
		
		$class = get_option("mail_chimp_class");
		$btnTxt = get_option("mail_chimp_button");
		$sTxt = get_option("mail_chimp_search");
		$apikey = get_option("mail_chimp_key");
		$list = get_option("mail_chimp_list");
		$success = get_option("mail_chimp_success");
		if($btnTxt == ''){$btnTxt = 'Go';}
		if($class == ''){$class = 'mcForm';}
		if($sTxt == ''){$sTxt = 'Enter your email...';}
		if($success == ''){$success = "You're in, you've been added to our email list.";}
		
	function mc_func(){ 	
		global $class; global $btnTxt; global $sTxt; global $list; global $success; global $apikey;
		return '<div id="result"></div><form class="'.$class.'" action="" id="invite" method="POST">
		<input type="text" placeholder="'.$sTxt.'" name="email" id="address" data-validate="validate(required, email)"/>
		<input type="submit" value="'.$btnTxt.'">
		</form>';
		};		
add_shortcode( 'mcsignup', 'mc_func' );		
add_action( 'wp_ajax_mailchimp_submit', 'mailchimp_callback' );
add_action( 'wp_ajax_nopriv_mailchimp_submit', 'mailchimp_callback' );

function mailchimp_callback() {
global $wpdb; 
$apiKey = get_option("mail_chimp_key");
$listId = get_option("mail_chimp_list");
$success = get_option("mail_chimp_success");
$email = $_POST['email'];
$datacenter = substr($apiKey, -3);
$double_optin=false;
$send_welcome= true;
$email_type = 'html';
$submit_url = "http://".$datacenter.".api.mailchimp.com/1.3/?method=listSubscribe";
$data = array(
'email_address'=>$email,
'apikey'=>$apiKey,
'id' => $listId,
'double_optin' => $double_optin,
'send_welcome' => $send_welcome,
'email_type' => $email_type
);
$payload = json_encode($data);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $submit_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($payload));
$result = curl_exec($ch);
curl_close ($ch);
$data = json_decode($result);
if ($data->error){
echo $data->error;
} else {
echo stripslashes($success);
}
die();
}


	wp_enqueue_script("jquery");  
	$handle ='mc_email';
	$src = plugins_url( '/mailchimp-sign-up/assets/js/mc_subscribe.js');
	$deps = array("jquery");
	wp_enqueue_script( $handle, $src, $deps);
	$src =  plugins_url( '/mailchimp-sign-up/assets/css/mailchimp_signup.css' );
    wp_enqueue_style( 'mc_style', $src);

	function setup_theme_admin_menus() {
		add_submenu_page('options-general.php','Mail Chimp Setup', 'MailChimp', 'manage_options','mail-chimp-setup', 'mailchimp_settings');
	}
	
add_filter("plugin_action_links_mailchimp-sign-up/mc-signup.php", 'settings_link' );	
function settings_link($links) { 
  $settings_link = '<a href="/wp-admin/options-general.php?page=mail-chimp-setup">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

	
	function mailchimp_settings() {		
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
	
		global $apikey; global $list; global $class; global $btnTxt; global $sTxt; global $success;
		if (isset($_POST["update_settings"])) {
			$apikey = esc_attr($_POST["key"]);
			$list = esc_attr($_POST["list"]);
			$class = esc_attr($_POST["class"]);
			$btnTxt = esc_attr($_POST["button"]);
			$sTxt = esc_attr($_POST["search"]);
			$success = esc_attr($_POST["success"]);
			update_option("mail_chimp_key", $apikey );
			update_option("mail_chimp_list", $list );
			update_option("mail_chimp_class", $class );
			update_option("mail_chimp_button", $btnTxt );
			update_option("mail_chimp_search", $sTxt );
			update_option("mail_chimp_success", $success);
			echo '<div id="message" class="updated" style="margin-top:25px;">Settings saved</div> ';
	
		}

?>
 <div style="margin:0px 0px 0px 10px;">
       <div style="margin:20px 0px 0px 0px;"><img src="<?php echo plugins_url( '/mailchimp-sign-up/assets/images/chimp.png'); ?>" /></div>
       <h2>MailChimp API Settings:</h2>
        <form method="POST" action=""> 
        <label for="key" style="display:block;  font-weight:900">API Key :</label> 
        <input type="text" name="key" value="<?php  echo $apikey ?>" size="36" /><br />
		<?php apiCheck(); ?>
		<hr />
    <h3>Form Options</h3>
        <label for="class" style="display:block;  font-weight:900">CSS Class:</label>   
        <input type="text" name="class" value="<?php  echo $class ; ?>" /><br />
        <label for="button" style="display:block;  font-weight:900">Button Text:</label>   
        <input type="text" name="button" value="<?php  echo $btnTxt ; ?>" /><br />
        <label for="class" style="display:block;  font-weight:900">Place Holder:</label>   
        <input type="text" name="search" value="<?php  echo $sTxt ; ?>" /> <br />
        <label for="class" style="display:block;  font-weight:900">Success / Thank You:</label>   
        <input type="text" name="success" value="<?php echo stripslashes($success); ?>" size="40" /> <br />
        		<script>
		jQuery.noConflict();
		(function ($) {
			$(function () {
				$(document).ready(function () {
		
					
					<?php global $list; if($list != ''){echo'$(".checkboxContainer").hide();';} ?>
					
					var val = '<?php global $list; echo $list; ?>';
					$('input:checkbox[value="' + val + '"]').attr('checked', true);		
					
					var chosenList = $(".checkboxContainer").find("input[type=checkbox][checked]").attr("data-id");
					
					$("input[type='checkbox']").change(function () {
						$(this).closest(".checkboxContainer").find("input[type='checkbox']").not(this).prop("checked", false);
						$(this).prop("checked", true);
					});
					
		
					$('#listToggle').html('Connected to: '+chosenList+' <span>View Lists</span>');
					$('#listToggle span').click(function(){
					$('.checkboxContainer').fadeIn();
					$('#listToggle').html('');
					});
					
				});
			});
		})(jQuery);</script>  

		<input type="hidden" name="update_settings" value="Y" /><br />
        <input type="submit" value="Save settings" class="button-primary"/>  
        </form>  
        <div style="margin-top:20px;">Use shortcode <span style="font-size:15px; background:#373737; padding:2px 5px; color:#fff; font-weight:900;">[mcsignup]</span> to display the form.</div>

        <div style="font-size:11px; margin-top:10px;"><p><img src="<?php  echo plugins_url( '/mailchimp-sign-up/assets/images/jp_logo_orange.png'); ?>" style="float:left"/> MailChimp sign up plugin by <a href="http://jasonprescher.com/" style="color:#D16249; text-decoration:none; font-style:italic">Jason Prescher</a></p></div>
        </div> 
        </div> 
		<?php } add_action("admin_menu", "setup_theme_admin_menus"); ?>