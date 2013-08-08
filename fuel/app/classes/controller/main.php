<?php
class Controller_Main extends \Controller_Template
{
    public function action_index() {
       /*  Session::destroy();
        
        if ( ! is_null(Session::get('is_login')) && (Session::get('is_login') == 'True')) {
            Response::redirect('message');
        } */
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('main/index');
        
        /* $data['logins'] = Model_User::find_all();
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('main/index', $data); */
    }
    
    public function action_login() {
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
                        'select' => array('username', 'password', 'is_admin'),
                        'where' => array(
                            array('username', '=', $login->username,),
                            array('password', '=', $login->password,),
                        ),
                    ));
                    
                    if ( ! is_null($data)) {
                        Session::set('username', $login->username);
                        Session::set('is_login', 'True');
                        
                        //echo '<pre>'; print_r($data); //debug test
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
                        Response::redirect('login');
                    }
                } else {
                    Session::set_flash('error', 'Could not login.');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }
        
        $this->template->title = "User Login";
        $this->template->content = View::forge('main/login');
    }
    
    public function action_logout() {
        Session::destroy();
        
        $this->template->title = "Logout Successfully, Please Login Again";
        //$this->template->content = View::forge('main/login');
        $this->template->content = View::forge('main/index');
    }
    
    public function action_create_user() {
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
                Session::set_flash('error', $val->error());
            }
        }
        
        $this->template->title = "Create A New User";
        $this->template->content = View::forge('main/create_user');
    }
}
