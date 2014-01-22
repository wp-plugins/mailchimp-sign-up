<?php 

	function apiCheck(){
	$apikey = get_option("mail_chimp_key");
	$list = get_option("mail_chimp_list");
	$btnTxt = get_option("mail_chimp_button");
	$sTxt = get_option("mail_chimp_search");	
	$apiUrl = 'https://' . substr($apikey, -3) . '.api.mailchimp.com/2.0/';
			
			$data['apikey'] = $apikey;
			$url = $apiUrl . 'helper/ping.json';
			$json = wp_remote_post($url, array( 'body' => $data,'timeout' => 10,'headers' => array('Accept-Encoding' => ''),'sslverify' => false) );
			$result = wp_remote_retrieve_body($json);
			$obj = json_decode($result, TRUE);
			$status = $obj['msg'];
			if ($status == 'Everything\'s Chimpy!'){ 
			echo '<div id="checkbox" class="updated" style="margin-left:0px; max-width:270px; background:#52A2DA; color:#FFFF57; border-left:4px solid #222;" >MailChimp Connected <a href="http://admin.mailchimp.com/account/api" target="_blank" style="color:#222;">Change key</a></div>';
			/* If API is good display settings */
			lists($apikey,$apiUrl); 
			}else{
			 echo ' <a href="http://admin.mailchimp.com/account/api" target="_blank">Get your MailChimp API key here</a><br />';
			}
	}

function lists($apikey,$apiUrl){				
			$data['apikey'] = $apikey;
			$url = $apiUrl . 'lists/list.json';
			$json = wp_remote_post($url, array( 'body' => $data,'timeout' => 10,'headers' => array('Accept-Encoding' => ''),'sslverify' => false) );
			$result = wp_remote_retrieve_body($json);
			$obj = json_decode($result, TRUE);
			echo '<div class="checkboxContainer" style="max-width:300px;"><label for="list" ><h3>Choose Your List</h3></label>';	
			for($i=0; $i<count($obj['data']); $i++) {
			$listID = $obj['data'][$i]["id"];
			$listName = $obj['data'][$i]["name"];
		    echo '<input class="listCheckbox" type="checkbox" name="list" data-id="'.$listName.'" value="'.$listID.'">'. $listName .'<br />' ;	
			}
			 echo '</div>';
			 echo '<div id="listToggle"></div>';
		}

?>