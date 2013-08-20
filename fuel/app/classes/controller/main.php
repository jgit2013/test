<?php
/**
 * Main Controller class
 *
 * 登入頁面的主要Controller
 *
 * @author    J
 */
class Controller_Main extends \Controller_Template
{
    private $captcha_driver = 'recaptcha';
    
    /**
     * 將頁面導向views/main/index.php，
     * 內容為主要的頁面，可選擇登入或建立新的使用者
     */
    public function action_index()
    {
        if ( ! is_null(Session::get('is_sign_in'))) {
            if (Session::get('is_admin') == '1') {
                Response::redirect('admin');
            } else {
                Response::redirect('message');
            }
        }
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/sign_in.php，
     * 若未登入時顯示登入頁面， 
     * 若使用者名稱或密碼錯誤時顯示錯誤訊息
     */
    public function action_sign_in()
    {
        $is_username_or_password_incorrect = false;
        $is_captcha_incorrect = false;
        
        if (Input::method() == 'POST') {
            $val = Model_User::validate('sign_in');
            
            if ($val->run()) {
                $sign_in = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => md5(Input::post('password'))
                ));
                
                if ($sign_in) {
                    $data['users'] = Model_User::find(array(
                        'select' => array('id', 'username', 'password', 'is_admin'),
                        'where' => array(
                            array('username', '=', $sign_in->username),
                            array('password', '=', $sign_in->password)
                        )
                    ));
                    
                    if ( ! is_null($data['users'])) {
                        $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
                        
                        if ( ! $is_captcha_incorrect) {
                            Session::set('ip_address', Input::real_ip());
                            
                            $date = Date::forge();
                            
                            $date->set_timezone('Asia/Taipei');
                            
                            Session::set('sign_in_timestamp', $date->get_timestamp());
                            Session::set('sign_in_time', $date->format('%Y-%m-%d %H:%M:%S'));
                            
                            Session::set('user_id', $data['users'][0]->id);
                            Session::set('username', $sign_in->username);
                            Session::set('is_sign_in', 'True');
                            
                            // echo '<pre>'; var_dump($data); //debug test
                            // echo $data['users'][0]->username.' '.$data['users'][0]->password.'<br/>'; //debug test
                            // echo Session::get('username').' '.Session::get('is_admin').'<br/>'; //debug test
                            
                            if ($data['users'][0]->is_admin == 1) {
                                Session::set('is_admin', '1');
                                
                                Response::redirect('admin');
                            } else {
                                Session::set('is_admin', '0');
                                
                                Response::redirect('message');
                            }
                        }
                    } else {
                        $is_username_or_password_incorrect = true;
                    }
                } else {
                    Session::set_flash('error', 'Could not sign in.');
                }
            } else {
                $is_username_or_password_incorrect = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        if ($is_username_or_password_incorrect) {
            $this->template->title = "Sign In >> Incorrect Username Or Password";
            $this->template->content = View::forge('main/sign_in');
        } else {
            if ($is_captcha_incorrect) {
                $this->template->title = "Sign In >> The Captcha is Incorrect";
                $this->template->content = View::forge('main/sign_in');
            }
            else {
                $this->template->title = "Sign In >> Admin User: Username=admin, Password=admin";
                $this->template->content = View::forge('main/sign_in');
            }
        }
    }
    
    /**
     * 登出使用者並銷毀該次的Session物件
     */
    public function action_sign_out()
    {
        $ip_address = Session::get('ip_address');
        
        $username = Session::get('username');
        
        $date = Date::forge();
        
        $date->set_timezone('Asia/Taipei');
        
        $sign_in_time = Session::get('sign_in_time');
        $sign_out_time = $date->format('%Y-%m-%d %H:%M:%S');
        
        $sign_in_timestamp = (int) Session::get('sign_in_timestamp');
        $sign_out_timestamp = $date->get_timestamp();
        
        $during = Model_UserLog::time_elapsed($sign_out_timestamp - $sign_in_timestamp);
        
        Model_UserLog::save_log(
            $ip_address,
            $username,
            $sign_in_time,
            $sign_out_time,
            $during
        );
        
        Session::destroy();
        
        $this->template->title = "Goodbye  ~~~~ \"".$username."\"";
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/create_user.php，
     * 若未建立新使用者時顯示建立新使用者的頁面， 
     * 若使用者名稱或密碼長度不足時顯示錯誤訊息
     */
    public function action_create_user()
    {
        /*if (Input::method() == 'POST') {
             $val = Model_User::validate('create_user');
            
            if ($val->run()) {
                $input = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => md5(Input::post('password')),
                ));
                
                $users = Model_User::find(array(
                    'select' => array('username'),
                ));
                
                foreach ($users as $user) {
                    if ($input->username == $user->username) {
                        $is_username_in_use = true;
                        
                        break;
                    }
                }
                
                $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
                
                if ( ! $is_username_in_use && ! $is_captcha_incorrect) {
                    if ($input and $input->save()) {
                        Session::set_flash('success', 'Added user # '.$input->id.'.');
                        
                        Response::redirect('main/go');
                    } else {
                        Session::set_flash('error', 'Could not save user.');
                    }
                }
            } else {
                $is_username_or_password_too_short = true;
                
                Session::set_flash('error', $val->error());
            }
        } */
        
        $is_captcha_incorrect = false;
        $is_username_in_use = false;
        $is_username_or_password_too_short = false;
        
        if (Input::method() == 'POST') {
            $is_captcha_incorrect = ! Captcha::forge($this->captcha_driver)->check();
            
            if ( ! $is_captcha_incorrect) {
                $check_user_in_use = Model_User::check_user_in_use(
                    Input::post('username'),
                    Input::post('password')
                );
                
                if ($check_user_in_use == 'ERROR') {
                    $is_username_or_password_too_short = true;
                } else if ($check_user_in_use == 'IN USE') {
                    $is_username_in_use = true;
                } else {
                    Response::redirect('main/go');
                }
            }
        }
        
        $this->template->set_global('captcha_driver', $this->captcha_driver, false);
        
        if ($is_captcha_incorrect || $is_username_or_password_too_short) {
            $this->template->title = "Create A New User >> Your Username Should Be At Least \"1\" Character,
                                                     And Your Password Should Be At Least \"4\" Characters";
            $this->template->content = View::forge('main/create_user');
        } else {
            if ($is_username_in_use) {
                $this->template->title = "Create A New User >> Your Username Is Already In Use";
                $this->template->content = View::forge('main/create_user');
            } else {
                $this->template->title = "Create A New User";
                $this->template->content = View::forge('main/create_user');
            }
        }
        
        /* if ($is_username_in_use) {
            $this->template->title = "Create A New User >> Your Username Is Already In Use";
            $this->template->content = View::forge('main/create_user');
        } else {
            if ($is_username_or_password_too_short) {
                $this->template->title = "Create A New User >> Your Username Should Be At Least \"1\" Character,
                                                          And Your Password Should Be At Least \"4\" Characters";
                
                $this->template->content = View::forge('main/create_user');
            } else {
                if ($is_captcha_incorrect) {
                    $this->template->title = "Create A New User >> The Captcha is Incorrect";
                    $this->template->content = View::forge('main/create_user');
                }
                else {
                    $this->template->title = "Create A New User";
                    $this->template->content = View::forge('main/create_user');
                }
            }
        } */
    }
    
    /**
     * 將頁面導向views/main/go.php
     */
    public function action_go()
    {
        $this->template->title = "Create Successfully, Please Sign In";
        $this->template->content = View::forge('main/go');
    }
}
