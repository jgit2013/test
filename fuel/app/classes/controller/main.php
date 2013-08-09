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
     * 
     * 將頁面導到views/main/index.php，內容為主要的頁面，可選擇登入或建立新的使用者
     * 
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
     *
     * 將頁面導到views/main/login.php，若未登入時顯示登入頁面， 
     * 若使用者名稱或密碼錯誤時顯示錯誤訊息
     *
     */
    public function action_login()
    {
        $is_incorrect_username_or_password = false;
        
        if (Input::method() == 'POST') {
            $val = Model_User::validate('login');
            
            if ($val->run()) {
                $login = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => Input::post('password'),
                ));
                
                //echo $login->username.' '.$login->password.'<br/>'; //debug test
                
                if ($login) {
                    $data['users'] = Model_User::find(array(
                        'select' => array('id', 'username', 'password', 'is_admin'),
                        'where' => array(
                            array('username', '=', $login->username,),
                            array('password', '=', $login->password,),
                        ),
                    ));
                    
                    if ( ! is_null($data['users'])) {
                        Session::set('user_id', $data['users'][0]->id);
                        Session::set('username', $login->username);
                        Session::set('is_login', 'True');
                        
                        //echo '<pre>'; var_dump($data); //debug test
                        //echo $data['users'][0]->username.' '.$data['users'][0]->password.'<br/>'; //debug test
                        //echo Session::get('username').' '.Session::get('is_admin').'<br/>'; //debug test
                        
                        if ($data['users'][0]->is_admin == 1) {
                            Session::set('is_admin', '1');
                            
                            Response::redirect('admin');
                        } else {
                            Session::set('is_admin', '0');
                            
                            Response::redirect('message');
                        }
                    } else {
                        //echo '<pre>'; var_dump($data); //debug test
                        
                        $is_incorrect_username_or_password = true;
                        
                        //Response::redirect('main');
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
            $this->template->title = "Incorrect Username Or Password, Please Login Again";
            $this->template->content = View::forge('main/login');
        } else {
            $this->template->title = "User Login";
            $this->template->content = View::forge('main/login');
        }
    }
    
    /**
     *
     * 將頁面導到views/main/index.php，登出使用者並銷毀該次的Session物件
     *
     */
    public function action_logout()
    {
        $username = Session::get('username');
        
        Session::destroy();
        
        $this->template->title = "Goodbye \"".$username."\", Please Login Again";
        //$this->template->content = View::forge('main/login');
        $this->template->content = View::forge('main/index');
    }
    
    /**
     *
     * 將頁面導到views/main/create_user.php，若未建立新使用者時顯示建立新使用者的頁面， 
     * 若使用者名稱或密碼長度不足時顯示錯誤訊息
     *
     */
    public function action_create_user()
    {
        $is_username_or_password_too_short = false;
        
        if (Input::method() == 'POST') {
            $val = Model_User::validate('create');
            
            if ($val->run()) {
                $user = Model_User::forge(array(
                    'username' => Input::post('username'),
                    'password' => Input::post('password'),
                ));
                
                if ($user and $user->save()) {
                    Session::set_flash('success', 'Added user # '.$user->id.'.');
                    
                    Response::redirect('message');
                } else {
                    Session::set_flash('error', 'Could not save user.');
                }
            } else {
                $is_username_or_password_too_short = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        if ($is_username_or_password_too_short) {
            $this->template->title = "Your Username Should Be At Least \"1\" Character, And Your Password Should Be At Least \"4\" Characters";
            $this->template->content = View::forge('main/create_user');
        } else {
            $this->template->title = "Create A New User";
            $this->template->content = View::forge('main/create_user');
        }
    }
}
