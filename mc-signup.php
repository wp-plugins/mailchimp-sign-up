<?php 
 /**
 * Plugin Name: MailChimp Sign Up
 * Plugin URI: http://mailchimp-sign-up.volk.io/
 * Description: MailChimp signup form. Simple Ajax email input. 
 * Version: 1.2.1
 * Author: <a href="http://volk.io">Volk</a>
 * Contributors: jprescher
 * Author URI: http://volk.io/
 * License: A "Slug" license name e.g. GPL2
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
	require ('includes/api.php'); 
		
		$class = get_option("mail_chimp_class");
		$btnTxt = get_option("mail_chimp_button");
		$sTxt = get_option("mcs_placeholder");
		$apikey = get_option("mail_chimp_key");
		$list = get_option("mail_chimp_list");
		$success = get_option("mail_chimp_success");
		if($btnTxt == ''){$btnTxt = 'Go';}
		if($class == ''){$class = 'mcForm';}
		if($sTxt == ''){$sTxt = 'Enter your email...';}
		if($success == ''){$success = "You're in, you've been added to our email list.";}
		
	function mc_func(){ 	
		global $class; global $btnTxt; global $sTxt; global $list; global $success; global $apikey;
		return '<div id="mcs_result"></div><form class="'.$class.'" action="" id="mcs_invite" method="POST">
		<input type="text" placeholder="'.$sTxt.'" name="email" id="mcs_address" data-validate="validate(required, email)"/>
		<input type="submit" value="'.$btnTxt.'">
		</form>';
		};		
add_shortcode( 'mcsignup', 'mc_func' );		
add_action( 'wp_ajax_mcs_signup_submit', 'mcs_signup_callback' );
add_action( 'wp_ajax_nopriv_mcs_signup_submit', 'mcs_signup_callback' );

function mcs_signup_callback() {
global $wpdb; 
$apiKey = get_option("mail_chimp_key");
$listId = get_option("mail_chimp_list");
$success = get_option("mail_chimp_success");
$email = sanitize_text_field($_POST['mcs_email']);
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
    add_action( 'init', 'import_mcs_scripts' );
    
    function import_mcs_scripts() {
	wp_enqueue_script("jquery");  
	wp_enqueue_style( 'mcs_style', plugins_url( 'assets/css/mailchimp_signup.css', __FILE__ ));
	wp_register_script(  'mcs_script', plugins_url( 'assets/js/mc_subscribe.js', __FILE__), array( 'jquery' ),'', true );
	wp_localize_script( 'mcs_script', 'mcsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	wp_enqueue_script( 'mcs_script' );
   }



add_action( 'admin_enqueue_scripts', 'mcs_add_color_picker' );
function mcs_add_color_picker( $hook ) {
 
    if( is_admin() ) {      
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker');
    }
}



	$handle ='mc_email';
	$src = 
	$deps = array("jquery");

    

	function mcs_theme_admin_menus() {
		add_submenu_page('options-general.php','Mail Chimp Setup', 'MailChimp', 'manage_options','mail-chimp-setup', 'mcs_mailchimp_settings');
	}
	
add_filter("plugin_action_links_mailchimp-sign-up/mc-signup.php", 'mcs_settings_link' );	
function mcs_settings_link($links) { 
  $mcs_settings_link = '<a href="'.admin_url('options-general.php?page=mail-chimp-setup').'">Settings</a>'; 
  array_unshift($links, $mcs_settings_link); 
  return $links; 
}

	
	function mcs_mailchimp_settings() {		
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
	
		global $apikey; global $list; global $class; global $btnTxt; global $sTxt; global $success;
		if (isset($_POST["update_settings"])) {
			$apikey = sanitize_text_field($_POST["mcs_key"]);
			$list = sanitize_text_field($_POST["mcs_list"]);
			$class = sanitize_text_field($_POST["mcs_class"]);
			$btnTxt = sanitize_text_field($_POST["mcs_button"]);
			$sTxt = sanitize_text_field($_POST["mcs_placeholder"]);
			$success = sanitize_text_field($_POST["mcs_success"]);

			if($btnTxt == ''){$btnTxt = 'Go';}
			if($class == ''){$class = 'mcForm';}
			if($sTxt == ''){$sTxt = 'Enter your email...';}
			if($success == ''){$success = "You're in, you've been added to our email list.";}
			


			update_option("mail_chimp_key", $apikey );
			update_option("mail_chimp_list", $list );
			update_option("mail_chimp_class", $class );
			update_option("mail_chimp_button", $btnTxt );
			update_option("mcs_placeholder", $sTxt );
			update_option("mail_chimp_success", $success);

			update_option("mcs_button_color", sanitize_text_field($_POST["mcs_button_color"]));
			update_option("mcs_button_hover_color", sanitize_text_field($_POST["mcs_button_hover_color"]));
			update_option("mcs_button_border_color", sanitize_text_field($_POST["mcs_button_border_color"]));
			update_option("mcs_form_text_color", sanitize_text_field($_POST["mcs_form_text_color"]));
			update_option("mcs_form_background_color", sanitize_text_field($_POST["mcs_form_background_color"]));
			update_option("mcs_form_border_color", sanitize_text_field($_POST["mcs_form_border_color"]));
			update_option("mcs_border_size", sanitize_text_field($_POST["mcs_border_size"]));
			update_option("mcs_padding_size", sanitize_text_field($_POST["mcs_padding_size"]));
			update_option("mcs_margin_size", sanitize_text_field($_POST["mcs_margin_size"]));
			update_option("mcs_button_text_color", sanitize_text_field($_POST["mcs_button_text_color"]));

			

			echo '<div id="message" class="updated" style="margin-top:25px;">Settings saved</div> ';
	
		}

		    $mcs_button_color = get_option('mcs_button_color');
			if ($mcs_button_color == '') {
			$mcs_button_color = '#777';
			} 

			$mcs_button_hover_color = get_option('mcs_button_hover_color');
			if ($mcs_button_hover_color == '') {
			$mcs_button_hover_color = '#555';
			}

			$mcs_button_border_color = get_option('mcs_button_border_color');
			if ($mcs_button_border_color == '') {
			$mcs_button_border_color = '#777';
			}
			$mcs_form_text_color = get_option('mcs_form_text_color');
			if ($mcs_form_text_color == '') {
			$mcs_form_text_color = '#555';
			}

			$mcs_form_background_color = get_option('mcs_form_background_color');
			if ($mcs_form_background_color == '') {
			$mcs_form_background_color = '#fff';
			}

			$mcs_form_border_color = get_option('mcs_form_border_color');
			if ($mcs_form_border_color == '') {
			$mcs_form_border_color = '#555';
			}

			$mcs_padding_size = get_option('mcs_padding_size');
			if ($mcs_padding_size == '') {
				$mcs_padding_size = 0;
				update_option("mcs_padding_size", $mcs_padding_size);
			}
			$mcs_margin_size = get_option('mcs_margin_size');
			if ($mcs_margin_size == '') {
				$mcs_margin_size = 0;
				update_option("mcs_margin_size",$mcs_margin_size);
			}		

			$mcs_border_size = get_option('mcs_border_size');
			if ($mcs_border_size == '') {
				$mcs_border_size = 0;
				update_option("mcs_border_size", $mcs_border_size);
			}

			$mcs_button_text_color = get_option('mcs_button_text_color');
			if ($mcs_button_text_color == '') {
			$mcs_button_text_color = '#fff';
			}


?>
 <div style="margin:0px 0px 0px 10px;">
       <div style="margin:20px 0px 0px 0px;"><img src="<?php echo plugins_url( 'assets/images/chimp.png',__FILE__); ?>" /></div>
       <h2>MailChimp API Settings:</h2>
        <form method="POST" action=""> 
        <label for="mcs_key" style="display:block;  font-weight:900">API Key :</label> 
        <input type="text" name="mcs_key" value="<?php  echo $apikey ?>" size="36" /><br />
		<?php mcs_apiCheck(); ?>
		<hr />
        <h3>Form Options</h3>
     
        <label for="mcs_button" style="display:block;  font-weight:900">Button Text:</label>   
        <input type="text" name="mcs_button" value="<?php  echo $btnTxt ; ?>" /><br />
        <label for="mcs_placeholder" style="display:block;  font-weight:900">Place Holder:</label>   
        <input type="text" name="mcs_placeholder" value="<?php  echo $sTxt ; ?>" /> <br />
        <label for="success" style="display:block;  font-weight:900">Success / Thank You:</label>  
        <input type="text" name="mcs_success" value="<?php echo stripslashes($success); ?>" size="40" /> <br />
      <hr /> 
      <div class="mcs_group"></div>
		<h3>Style Editor</h3>
		<div id="mcs_style_editor">

		<div class="mcs_style_picker">	
		<label for="mcs_button_color">Button Color:</label> 
		<input type="text" value="<? echo $mcs_button_color; ?>" name="mcs_button_color"  id="mcs_button_color" class="mcs-color-field" />
		</div>	
		<div class="mcs_style_picker">	
		<label for="mcs_button_hover_color">Button Hover Color:</label> 
		<input type="text" value="<? echo $mcs_button_hover_color; ?>" name="mcs_button_hover_color"  id="mcs_button_hover_color" class="mcs-color-field" />
		</div>
		<div class="mcs_style_picker">	
		<label for="mcs_button_border_color">Button Border Color:</label> 
		<input type="text" value="<? echo $mcs_button_border_color; ?>" name="mcs_button_border_color"  id="mcs_button_border_color" class="mcs-color-field" />
		</div>
		
		<div class="mcs_style_picker">	
		<label for="mcs_button_text_color">Button Text Color:</label> 
		<input type="text" value="<? echo $mcs_button_text_color; ?>" name="mcs_button_text_color"  id="mcs_button_text_color" class="mcs-color-field" />
		</div>

		<div class="mcs_style_picker">	
		<label for="mcs_form_text_color">Field Text Color:</label> 
		<input type="text" value="<? echo $mcs_form_text_color; ?>" name="mcs_form_text_color"  id="mcs_form_text_color" class="mcs-color-field" />
		</div>
		<div class="mcs_style_picker">	
		<label for="mcs_form_background_color">Field BG Color:</label> 
		<input type="text" value="<? echo $mcs_form_background_color; ?>" name="mcs_form_background_color"  id="mcs_form_background_color" class="mcs-color-field" />
		</div>
		<div class="mcs_style_picker">	
		<label for="mcs_form_border_color">Field Border Color:</label> 
		<input type="text" value="<? echo $mcs_form_border_color; ?>" name="mcs_form_border_color"  id="mcs_form_border_color" class="mcs-color-field" />
		</div>




		<div class="mcs_group"></div>
		<div class="mcs_style_picker">		
        <label for="mcs_border_size">Border Size:</label>
		<input type="number" value="<? echo $mcs_border_size; ?>" style="width:60px;" name="mcs_border_size"  id="mcs_border_size" /> px 
        </div>
		<div class="mcs_style_picker">		
        <label for="mcs_padding_size">Padding:</label>
		 <input type="number" value="<? echo $mcs_padding_size; ?>" style="width:60px;" name="mcs_padding_size"  id="mcs_padding_size" /> px 
        </div>
        <div class="mcs_style_picker">		
        <label for="mcs_margin_size">Margin:</label>
		 <input type="number" value="<? echo $mcs_margin_size; ?>" style="width:60px;" name="mcs_margin_size"  id="mcs_margin_size" /> px 
        </div>
        <br />
        <div class="mcs_group"></div>
		<label for="mcs_class">CSS Class:</label>   
        <input type="text" name="mcs_class" value="<?php  echo $class ; ?>" /><i>&nbsp;<b>For advanced users only</b>. <a href="https://css-tricks.com/when-using-important-is-the-right-choice/" target="_blank">Overriding syles</a>.</i> <br />
        <div class="mcs_group"></div>
        </div>
			


        		<script>
		jQuery.noConflict();
		(function ($) {
			$(function () {
				$(document).ready(function () {
		
					
					<?php global $list; if($list != ''){echo'$(".mcs_checkboxContainer").hide();';} ?>
					
					var val = '<?php global $list; echo $list; ?>';
					$('input:checkbox[value="' + val + '"]').attr('checked', true);		
					
					var chosenList = $(".mcs_checkboxContainer").find("input[type=checkbox][checked]").attr("data-id");
					
					$("input[type='checkbox']").change(function () {
						$(this).closest(".mcs_checkboxContainer").find("input[type='checkbox']").not(this).prop("checked", false);
						$(this).prop("checked", true);
					});
					
		
					$('#mcs_listToggle').html('Connected to: '+chosenList+' <span>View Lists</span>');
					$('#mcs_listToggle span').click(function(){
					$('.mcs_checkboxContainer').fadeIn();
					$('#mcs_listToggle').html('');
					});
					
				});
			});
		})(jQuery);</script>  

		<input type="hidden" name="update_settings" value="Y" /><br />
        <input type="submit" value="Save settings" class="button-primary"/> 
        <br /><br />
        </form>
        <hr /> 
  <div class="mcs_group"></div>
        <h3>Usage</h3>
		<div style="margin-top:20px;">Use shortcode <span style="font-size:15px; background:#373737; padding:2px 5px; color:#fff; font-weight:900;">[mcsignup]</span> to display the form.</div>
        
        <br /><br /><hr /> 
        <div style="font-size:11px; margin-top:10px;"><p><img src="<?php  echo plugins_url( 'assets/images/volk_sm_logo.png',__FILE__); ?>" style="float:left;margin-right:10px;"/> MailChimp sign up plugin <i>via</i> <a href="http://volk.io/" style="color:#FB4348; font-weight:bold; text-decoration:none; font-style:italic">VOLK</a></p></div>
       
        
        <div class="mcs_group"></div>

<div class="mcs-donate-btn">
<h4>Love it?</h4>	
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="UVDLVRK9TH47S">
<input type="hidden" name="currency_code" value="USD">
<input type="image" src="http://volk.io/images/mcs-donate-btn.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
<table>
<tr><td>
<input type="hidden" name="on0" value="Donation Amount">
Donation Amount:
</td></tr><tr><td>
<select name="os0">
<option value="Thank You">Thank You $1.00 USD</option>
<option value="You're Amazing">You're Amazing $5.00 USD</option>
<option value="MUCH LOVE">MUCH LOVE $10.00 USD</option>
</select> </td></tr>
</table>
</form>
</div> 
 </div> 
<?php 
} 
add_action("admin_menu", "mcs_theme_admin_menus");


class mcs_customize {

public static function mcs_header_output() {
	      ?>
	      <!-- Mail Chimp Sign Up CSS--> 
	      <style type="text/css">
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button],.mcForm input[type=text]', 'border', 'mcs_border_size', '','px solid'); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button]', 'background-color', 'mcs_button_color', ''); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button]', 'color', 'mcs_button_text_color', ''); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button],.mcForm input[type=text]', 'margin', 'mcs_margin_size', '','px'); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=text]', 'padding', 'mcs_padding_size', '','px'); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button]', 'padding', 'mcs_padding_size', '','px'); ?>

	           <?php self::mcs_generate_css('.mcForm input[type=submit]:hover,.mcForm input[type=button]:hover', 'background-color', 'mcs_button_hover_color', ''); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=submit],.mcForm input[type=button]', 'border-color', 'mcs_button_border_color', ''); ?>
			   <?php self::mcs_generate_css('.mcForm input[type=submit]:hover,.mcForm input[type=button]:hover', 'border-color', 'mcs_button_hover_color', ''); ?>
	          
	           <?php self::mcs_generate_css('.mcForm input[type=text]', 'color', 'mcs_form_text_color', ''); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=text]', 'background-color', 'mcs_form_background_color', ''); ?>
	           <?php self::mcs_generate_css('.mcForm input[type=text]', 'border-color', 'mcs_form_border_color', ''); ?>

	     
         
	      </style> 
	      <!--/ Mail Chimp Sign Up CSS--> 
	      <?php
	   }

public static function mcs_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_option($mod_name);
      if($selector =='.mcForm input[type=submit],.mcForm input[type=button]' && $style == 'padding'){
      	$mod = get_option($mod_name) -1;
      }

      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }	   
}
add_action( 'wp_head' , array( 'mcs_customize' , 'mcs_header_output' ) );