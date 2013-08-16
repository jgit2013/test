<?php
/*
 * ------------------------------------------------------------------------------- 
 * Copyrights 2007 (c) WebSpamProtect.com 
 * -------------------------------------------------------------------------------
 */

// ///////// Settings ///////////
$YourEmail = "nfuima49641149@gmail.com"; // Add your e-mail here
$Subject = "Test WebSpamProtect.com"; // Add e-mail subject here
$ThanksMessage = "Thank you for contacting J!"; // Add your own 'thanks' message here
$ErrColor = "#CC0033"; // error text color
// ///////// End Settings ///////

session_start();

$formAction = $_POST["action"];
$formError = array();

if ($formAction == 'submit')
    submitForm();

showHeader();

if ($formAction == 'submit' && ! count($formError))
    showThanks();
else
    showForm();

showFooter();

/*
 * ------------------------------------------------------------------------------- Functions -------------------------------------------------------------------------------
 */

// You can add you own header here
function showHeader()
{
    $header = <<< END_HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Author" content="WebSpamProtect.com" />
</head>
<body>
END_HTML;
    
    echo $header;
}

// You can add you own footer here
function showFooter()
{
    $footer = <<< END_HTML
</body>
</html>
END_HTML;
    
    echo $footer;
}

// thanks message output
function showThanks()
{
    global $ThanksMessage;
    
    $html_thanks = <<< END_HTML
$ThanksMessage
END_HTML;
    
    echo $html_thanks;
}

// form output
function showForm()
{
    global $formError, $ErrColor;
    $name = "";
    $email = "";
    $website = "";
    $session_id = session_id();
    
    if ($formError) {
        $name = htmlspecialchars(trim($_POST["name"]));
        $email = htmlspecialchars(trim($_POST["email"]));
        $website = htmlspecialchars(trim($_POST["website"]));
        $message = htmlspecialchars(trim($_POST["message"]));
    }
    
    $html_form = <<< END_HTML
<form method="POST" action="contact_us.php" name="contact_form">
<input type="hidden" name="action" value="submit">
<table>
<tr>
  <td>Name: *</td>
  <td><input type="text" name="name" value="$name" maxlength="255"> <span style="color:{$ErrColor};">{$formError['name']}</span></td>
</tr>
<tr>
  <td>E-Mail: *</td>
  <td><input type="text" name="email" value="$email" maxlength="255"> <span style="color:{$ErrColor};">{$formError['email']}</span></td>
</tr>
<tr>
  <td>WebSite:</td>
  <td><input type="text" name="website" value="$website" maxlength="255"></td>
</tr>
<tr>
  <td>Message: *</td>
  <td><span style="color:{$ErrColor};">{$formError['message']}</span><textarea name="message" cols="37" rows="7">$message</textarea></td>
</tr>
<tr>
  <td>Confirmation Code:</td>
  <td><a href="http://webspamprotect.com" target="_blank"><img border="0" title="Protected by WebSpamProtect.com" src="wsp_get_captcha.php?s=$session_id" width="135" height="50"></a></td>
</tr>
<tr>
  <td>Enter code: *</td>
  <td><input type="text" name="code" value=""> <span style="color:{$ErrColor};">{$formError['code']}</span></td>
</tr>
<tr>
  <td colspan="2" align="right"><input type="submit" value="  Send  "></td>
</tr>
</table>
</form>
END_HTML;
    
    echo $html_form;
}

// form handler
function submitForm()
{
    global $formError, $YourEmail, $Subject;
    
    // get form fields
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $website = trim($_POST["website"]);
    $message = trim($_POST["message"]);
    $code = trim($_POST["code"]);
    $name = preg_replace("/[\n\r]+/", "", $name);
    $email = preg_replace("/[\n\r]+/", "", $email);
    $website = preg_replace("/[\n\r]+/", "", $website);
    $forbidden = array(
        "/MIME\-Version\:/i",
        "/Content\-Type\:/i",
        "/From\:/i",
        "/To\:/i",
        "/Cc\:/i",
        "/Bcc\:/i"
    );
    $name = preg_replace($forbidden, "", $name);
    $email = preg_replace($forbidden, "", $email);
    $website = preg_replace($forbidden, "", $website);
    $message = preg_replace($forbidden, "", $message);
    
    // validate form fields
    if (! $name)
        $formError['name'] = "Name is required.";
    if (! $email)
        $formError['email'] = "E-Mail is required.";
    elseif (! preg_match('/^([A-Z0-9]+[._]?){1,}[A-Z0-9]+\@(([A-Z0-9]+[-]?){1,}[A-Z0-9]+\.){1,}[A-Z]{2,4}$/i', $email)) {
        $formError['email'] = "E-Mail is invalid.";
    }
    if (! $message)
        $formError['message'] = "Message is required.<br>";
    if (! checkConfirmationCode($code))
        $formError['code'] = "Confirmation code is invalid.";
    if (count($formError))
        return;
        
        // build and send e-mail
    $body = "Name: $name\n";
    $body .= "E-Mail: $email\n";
    if ($website)
        $body .= "WebSite: $website\n";
    $body .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $body .= "Message:\n$message\n";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
    @mail($YourEmail, $Subject, $body, $headers);
}

function checkConfirmationCode($code)
{
    if (! $code || ($code != $_SESSION['verification_code']))
        return false;
    return true;
}
