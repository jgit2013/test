<?php
/*-------------------------------------------------------------------------------
         Copyrights 2006-2007 (c) WebSpamProtect.com
		 Filename : wsp_captcha.php
		 Version  : 1.2
 -------------------------------------------------------------------------------*/

/* This function will check captcha code entered by user and stop spam robots */
function WSP_CheckImageCode($WSP_ImgKey = '', $WSP_ImgCode = '') {
	if (!$WSP_ImgKey) {
		if (isset($_POST['wsp_key'])) $WSP_ImgKey = $_POST['wsp_key'];
		elseif (isset($_GET['wsp_key'])) $WSP_ImgKey = $_GET['wsp_key'];
	}
	if (!$WSP_ImgCode) {
		if (isset($_POST['wsp_code'])) $WSP_ImgCode = $_POST['wsp_code'];
		elseif (isset($_GET['wsp_code'])) $WSP_ImgCode = $_GET['wsp_code'];
	}

	if (!$WSP_ImgKey || (strlen($WSP_ImgKey) > 32)) return "Error: 'wsp_key' is invalid.";
	if (!$WSP_ImgCode || (strlen($WSP_ImgCode) > 10)) return "Error: 'wsp_code' is invalid.";

	$WSP_Params = "&imgkey=".urlencode($WSP_ImgKey)."&imgcode=".urlencode($WSP_ImgCode);
	$WSP_Result = WSP_GetData('checkcode.php', $WSP_Params);

	switch(intval($WSP_Result)) {
	case 200 :
		return "OK";
		break;
	case 404 :
		return "Error: The image code entered by user is invalid.";
		break;
	case 401 :
		return "Error: You are not authorized to use WebSpamProtect.com service.";
		break;
	case 501 :
		return "Error: It appears that your web host has disabled all functions for handling remote pages and as a result the WebSpamProtect.com software will not function on your web page. Please contact your web host for more information.";
		break;
	default :
		return "Unknown error.";
		break;
	}
}

function WSP_GetData($WSP_File = '', $WSP_Params = '') {
	$WSP_UserKey = "K266-624F-P41D-C804";
	$WSP_Version = "1.2";
	if (!$WSP_UserKey) return 401;
	$WSP_URL = "http://webspamprotect.com/".$WSP_File."?ver=".urlencode($WSP_Version)."&userkey=".urlencode($WSP_UserKey).$WSP_Params;

	if ((intval(get_cfg_var('allow_url_fopen')) || intval(ini_get('allow_url_fopen'))) && function_exists('file_get_contents')) {
		$WSP_Result = @file_get_contents($WSP_URL);
	} elseif ((intval(get_cfg_var('allow_url_fopen')) || intval(ini_get('allow_url_fopen'))) && function_exists('file')) {
		$content = @file($WSP_URL);
		$WSP_Result = @join('', $content);
	} elseif (function_exists('curl_init')) {
		$ch = curl_init($WSP_URL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$WSP_Result = curl_exec($ch);
		curl_close($ch);
	} else {
		return 501;
	}

	return $WSP_Result;
}

?>