<?php
/*
 * ------------------------------------------------------------------------------- 
 * Copyrights 2007 (c) WebSpamProtect.com 
 * -------------------------------------------------------------------------------
 */

// ///////// Settings ///////////
$text = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789'; // captcha chars
$bg_color = "#EEEEEE"; // background color
$font_color = "#003366"; // font color
$filling = true; // When this parameter is set to true, filling lines will be added.
$fill_color = "#6699CC"; // filling color
// ///////// End Settings ///////////

$session_id = $_GET['s'];
session_id($session_id);
session_start();

list ($usec, $sec) = explode(' ', microtime());
mt_srand(((float) $sec + ((float) $usec * 100000)));

$rnd_param_1 = mt_rand(700000, 1000000) / 15000000;
$rnd_param_2 = mt_rand(700000, 1000000) / 15000000;
$rnd_param_3 = mt_rand(700000, 1000000) / 15000000;
$rnd_param_4 = mt_rand(700000, 1000000) / 15000000;
$rnd_param_5 = mt_rand(0, 3141592) / 1000000;
$rnd_param_6 = mt_rand(0, 3141592) / 1000000;
$rnd_param_7 = mt_rand(0, 3141592) / 1000000;
$rnd_param_8 = mt_rand(0, 3141592) / 1000000;

$out_image = ImageCreateTrueColor(100, 35);
$bgcolordec = hex2dec($bg_color);
$fontcolordec = hex2dec($font_color);
$fillcolordec = hex2dec($fill_color);
$bgcolor = ImageColorAllocate($out_image, $bgcolordec[0], $bgcolordec[1], $bgcolordec[2]);
$fontcolor = ImageColorAllocate($out_image, $fontcolordec[0], $fontcolordec[1], $fontcolordec[2]);
$fillcolor = ImageColorAllocate($out_image, $fillcolordec[0], $fillcolordec[1], $fillcolordec[2]);

$image_text = "";
for ($i = 0; $i < 6; $i ++)
    $image_text .= $text{mt_rand(0, strlen($text) - 1)};

$image_src = ImageCreateTrueColor(100, 35);
ImageFill($image_src, 0, 0, $bgcolor);
ImageString($image_src, 5, 20, 10, $image_text, $fontcolor);
if ($filling) {
    ImageSetStyle($image_src, array(
        $fillcolor
    ));
    for ($i = 0; $i < 8; $i ++) {
        ImageLine($image_src, mt_rand($i * 10, ($i + 1) * 10), 1, mt_rand(($i - 1) * 10, $i * 10), 35, IMG_COLOR_STYLED);
    }
}

for ($x = 0; $x < 100; $x ++) {
    for ($y = 0; $y < 35; $y ++) {
        $sinx = $x + (sin($x * $rnd_param_1 + $rnd_param_5) + sin($y * $rnd_param_3 + $rnd_param_6)) * 4;
        $siny = $y + (sin($x * $rnd_param_2 + $rnd_param_7) + sin($y * $rnd_param_4 + $rnd_param_8)) * 4;
        if ($sinx < 0 || $siny < 0 || $sinx > 100 || $siny > 35) {
            $color = ImageColorAllocate($image_src, $bgcolordec[0], $bgcolordec[1], $bgcolordec[2]);
            $color_x = ImageColorAllocate($image_src, $bgcolordec[0], $bgcolordec[1], $bgcolordec[2]);
            $color_y = ImageColorAllocate($image_src, $bgcolordec[0], $bgcolordec[1], $bgcolordec[2]);
            $color_xy = ImageColorAllocate($image_src, $bgcolordec[0], $bgcolordec[1], $bgcolordec[2]);
        } else {
            $color = ImageColorAt($image_src, $sinx, $siny);
            $color_x = ImageColorAt($image_src, $sinx + 1, $siny);
            $color_y = ImageColorAt($image_src, $sinx, $siny + 1);
            $color_xy = ImageColorAt($image_src, $sinx + 1, $siny + 1);
        }
        
        if ($color == $color_x && $color == $color_y && $color == $color_xy) {
            $newcolor = $color;
            $r_newcolor = ($color >> 16) & 0xFF;
            $g_newcolor = ($color >> 8) & 0xFF;
            $b_newcolor = $color & 0xFF;
        } else {
            $asinx = $sinx - floor($sinx);
            $asiny = $siny - floor($siny);
            $asinx1 = 1 - $asinx;
            $asiny1 = 1 - $asiny;
            $r_color = ($color >> 16) & 0xFF;
            $g_color = ($color >> 8) & 0xFF;
            $b_color = $color & 0xFF;
            $r_color_x = ($color_x >> 16) & 0xFF;
            $g_color_x = ($color_x >> 8) & 0xFF;
            $b_color_x = $color_x & 0xFF;
            $r_color_y = ($color_y >> 16) & 0xFF;
            $g_color_y = ($color_y >> 8) & 0xFF;
            $b_color_y = $color_y & 0xFF;
            $r_color_xy = ($color_xy >> 16) & 0xFF;
            $g_color_xy = ($color_xy >> 8) & 0xFF;
            $b_color_xy = $color_xy & 0xFF;
            $r_newcolor = floor($r_color * $asinx1 * $asiny1 + $r_color_x * $asinx * $asiny1 + $r_color_y * $asinx1 * $asiny + $r_color_xy * $asinx * $asiny);
            $g_newcolor = floor($g_color * $asinx1 * $asiny1 + $g_color_x * $asinx * $asiny1 + $g_color_y * $asinx1 * $asiny + $g_color_xy * $asinx * $asiny);
            $b_newcolor = floor($b_color * $asinx1 * $asiny1 + $b_color_x * $asinx * $asiny1 + $b_color_y * $asinx1 * $asiny + $b_color_xy * $asinx * $asiny);
        }
        ImageSetPixel($out_image, $x, $y, ImageColorAllocate($out_image, $r_newcolor, $g_newcolor, $b_newcolor));
    }
}
ImageDestroy($image_src);

$image_sized = ImageCreateTrueColor(135, 50);
ImageCopyResampled($image_sized, $out_image, 0, 0, 0, 0, 135, 50, 100, 35);
ImageDestroy($out_image);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Content-Type: image/png");
ImagePNG($image_sized);
ImageDestroy($image_sized);

if (isset($_SESSION['verification_code']))
    unset($_SESSION['verification_code']);
$_SESSION['verification_code'] = $image_text;

function hex2dec($color)
{
    $color = trim($color, "#");
    $r = hexdec(substr($color, 0, 2));
    $g = hexdec(substr($color, 2, 2));
    $b = hexdec(substr($color, 4, 2));
    return array(
        $r,
        $g,
        $b
    );
}
