<?php
/**
 * Captcha Controller class
 *
 * 提供simplecaptcha影像的Controller
 *
 * @author    J
 */
class Controller_Captcha extends \Controller_Template
{
    /**
     * 回傳表示此captcha影像的回應物件
     * 
     * @return  object  Response物件
     */
    public function action_simplecaptcha()
    {
        return Captcha::forge('simplecaptcha')->image();
        
        //custom simplecaptcha image setting
        /* return Captcha::forge('simplecaptcha')->image(array(
            'captcha_length' => 10,
            'captcha_width' => 230,
            'captcha_height' => 80,
            'background_rgba' => array(150, 150, 150, 0),
            'image_type' => 'png',
            'font_rgba' => array(0, 0, 0, 0),
            'font_height' => 30,
            'font_smooth' => true,
            'font_smooth_level' => 6,
            'font_gaussian_blur' => true,
            'font_png_width' => 880,
            'font_png_height' => 30,
            'distort' => true,
            'distort_multiplier' => 2,
            'distort_amplitude' => 15,
            'distort_amplitude_flip' => true,
            'distort_period' => 15,
            'message' => true,
            'message_height' => 15,
            'message_text_rgba' => array(255, 255, 255, 0),
            'message_background_rgba' => array(0, 0, 0, 0),
            'message_string' => 'Please Input What You See',
            'message_string_offset' => array(15, 0),
            'message_font_size' => 4,
        )); */
        
        //simplecaptcha image default setting
        /* return Captcha::forge('simplecaptcha')->image(array(
            'captcha_length' => 6,
            'captcha_width' => 130,
            'captcha_height' => 50,
            'background_rgba' => array(255, 255, 255, 0),
            'image_type' => 'png',
            'font_rgba' => array(0, 0, 0, 0),
            'font_height' => 30,
            'font_smooth' => true,
            'font_smooth_level' => 6,
            'font_gaussian_blur' => true,
            'font_png_width' => 880,
            'font_png_height' => 30,
            'distort' => true,
            'distort_multiplier' => 2,
            'distort_amplitude' => 15,
            'distort_amplitude_flip' => true,
            'distort_period' => 30,
            'message' => true,
            'message_height' => 15,
            'message_text_rgba' => array(255, 255, 255, 0),
            'message_background_rgba' => array(0, 0, 0, 0),
            'message_string' => 'Fuel Captcha Package',
            'message_string_offset' => array(5, 0),
            'message_font_size' => 2,
        )); */
    }
}