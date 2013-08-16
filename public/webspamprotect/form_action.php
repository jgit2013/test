<?php
include_once("wsp_captcha.php");

if (WSP_CheckImageCode() != "OK") {
    die("The image code you have entered is incorrect. Please, click the 'Back' button of your browser and type the correct one.");
}
else {
	echo 'OK';
}
