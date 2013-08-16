<?php
class Controller_Captcha extends \Controller_Template
{
    public function action_index()
    {
        $is_incorrect_username_or_password = false;
        $captcha = false;
        
        if (Input::method() == 'POST') {
            //$captcha = Captcha::forge('simplecaptcha');
            $captcha = Captcha::forge('recaptcha')->check();
            
            //echo $captcha;
            
            $val = Model_User::validate('sign_in');
            
            if ($val->run()) {
                $login = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => Input::post('password'),
                ));
                
                if ($login) {
                    $data['users'] = Model_User::find(array(
                        'select' => array('id', 'username', 'password', 'is_admin'),
                        'where' => array(
                            array('username', '=', $login->username,),
                            array('password', '=', $login->password,),
                        ),
                    ));
                    
                    if ( ! is_null($data['users'])) {
                        Session::set('sign_in_time', time());
                        Session::set('sign_in_time_date', date("Y-m-d H:i:s"));
                        
                        Session::set('user_id', $data['users'][0]->id);
                        Session::set('username', $login->username);
                        Session::set('is_login', 'True');
                        
                        if ($data['users'][0]->is_admin == 1) {
                            Session::set('is_admin', '1');
                            
                            Response::redirect('admin');
                        } else {
                            Session::set('is_admin', '0');
                            
                            Response::redirect('message');
                        }
                    } else {
                        $is_incorrect_username_or_password = true;
                    }
                } else {
                    Session::set_flash('error', 'Could not login.');
                }
            } else {
                $is_incorrect_username_or_password = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        if ($is_incorrect_username_or_password) {
            $this->template->title = "Incorrect Username Or Password";
            $this->template->content = View::forge('captcha/login');
        } else if ($captcha) {
            $this->template->title = "OK";
            $this->template->content = View::forge('captcha/login');
        } else {
            $this->template->title = "Sign In (Admin User: Username=admin, Password=admin)";
            $this->template->content = View::forge('captcha/login');
        }
    }
    
    public function action_simplecaptcha()
    {
        return Captcha::forge('simplecaptcha')->image();
    }
}
