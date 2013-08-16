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
    /**
     * 將頁面導向views/main/index.php，內容為主要的頁面，可選擇登入或建立新的使用者
     */
    public function action_index()
    {
       /*  Session::destroy();
        
        if ( ! is_null(Session::get('is_login')) && (Session::get('is_login') == 'True')) {
            Response::redirect('message');
        } */
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/login.php，若未登入時顯示登入頁面， 
     * 若使用者名稱或密碼錯誤時顯示錯誤訊息
     */
    public function action_login()
    {
        $is_username_or_password_incorrect = false;
        $is_captcha_incorrect = false;
        
        if (Input::method() == 'POST') {
            //$is_captcha_incorrect = Captcha::forge()->check();
            
            /* if ( ! $is_captcha_incorrect) {
                
            } */
            
            $val = Model_User::validate('sign_in');
            
            if ($val->run()) {
                $login = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => Input::post('password')
                ));
                
                // echo $login->username.' '.$login->password.'<br/>'; //debug test
                
                if ($login) {
                    $data['users'] = Model_User::find(array(
                        'select' => array('id', 'username', 'password', 'is_admin'),
                        'where' => array(
                            array('username', '=', $login->username),
                            array('password', '=', $login->password)
                        )
                    ));
                    
                    if (! is_null($data['users'])) {
                        $is_captcha_incorrect = Captcha::forge()->check();
                        
                        if ($is_captcha_incorrect) {
                            echo 'Yes';
                        } else {
                            echo 'No';
                        }
                        
                        exit;
                        
                        if ( ! $is_captcha_incorrect) {
                            Session::set('sign_in_time', time());
                            Session::set('sign_in_time_date', date("Y-m-d H:i:s"));
                            
                            Session::set('user_id', $data['users'][0]->id);
                            Session::set('username', $login->username);
                            Session::set('is_login', 'True');
                            
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
                    Session::set_flash('error', 'Could not login.');
                }
            } else {
                $is_username_or_password_incorrect = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        if ($is_username_or_password_incorrect) {
            $this->template->title = "Incorrect Username Or Password";
            $this->template->content = View::forge('main/login');
        } else {
            /* if (Input::method() == 'POST') {
                $is_captcha_incorrect = Captcha::forge()->check();
            } */
            
            if ($is_captcha_incorrect) {
                $this->template->title = "The Captcha is Incorrect";
                $this->template->content = View::forge('main/login');
            }
            else {
                $this->template->title = "Sign In (Admin User: Username=admin, Password=admin)";
                $this->template->content = View::forge('main/login');
            }
        }
    }
    
    /**
     * 登出使用者並銷毀該次的Session物件
     */
    public function action_logout()
    {
        $username = Session::get('username');
        
        $sign_in_time_date = Session::get('sign_in_time_date');
        $sign_out_time_date = date("Y-m-d H:i:s");
        
        $sign_in_time = (int) Session::get('sign_in_time');
        $sign_out_time = time();
        
        $during = Model_UserLog::time_elapsed($sign_out_time - $sign_in_time);
        
       Model_UserLog::save_log(
            $username,
            $sign_in_time_date,
            $sign_out_time_date,
            $during
        );
        
        Session::destroy();
        
        $this->template->title = "Goodbye  ~~~~ \"".$username."\"";
        //$this->template->content = View::forge('main/login');
        $this->template->content = View::forge('main/index');
    }
    
    /**
     * 將頁面導向views/main/create_user.php，若未建立新使用者時顯示建立新使用者的頁面， 
     * 若使用者名稱或密碼長度不足時顯示錯誤訊息
     */
    public function action_create_user()
    {
        $is_username_in_use = false;
        $is_username_or_password_too_short = false;
        $is_captcha_incorrect = false;
        
        if (Input::method() == 'POST') {
            $val = Model_User::validate('create_user');
            
            if ($val->run()) {
                $input = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => Input::post('password'),
                ));
                
                $users = Model_User::find(array(
                    'select' => array('username'),
                ));
                
                //echo '<pre>'; print_r($users);
                
                foreach ($users as $user) {
                    if ($input->username == $user->username) {
                        $is_username_in_use = true;
                        
                        break;
                    }
                }
                
                if ( ! $is_username_in_use && $is_captcha_incorrect) {
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
        }
        
        if ($is_username_in_use) {
            $this->template->title = "Your Username Is Already In Use";
            $this->template->content = View::forge('main/create_user');
        } else {
            if ($is_username_or_password_too_short) {
                $this->template->title = "Your Username Should Be At Least \"1\" Character,
                                                          And Your Password Should Be At Least \"4\" Characters";
            
                $this->template->content = View::forge('main/create_user');
            } else {
                $this->template->title = "Create A New User";
                $this->template->content = View::forge('main/create_user');
            }
        }
    }
    
    /**
     * 將頁面導向views/main/go.php
     */
    public function action_go()
    {
        $this->template->title = "Create Successfully, Please Sign In";
        $this->template->content = View::forge('main/go');
    }
    
    public function action_simplecaptcha()
    {
        return Captcha::forge('simplecaptcha')->image();
    }
}
