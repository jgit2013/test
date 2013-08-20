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
    }
}