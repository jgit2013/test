<?php
class Controller_Login extends \Controller_Template
{
    public function action_index() {
       /*  Session::destroy();
        
        if ( ! is_null(Session::get('is_login')) && (Session::get('is_login') == 'True')) {
            Response::redirect('message');
        } */
        
        $data['logins'] = Model_User::find_all();
        
        $this->template->title = "Main Page";
        $this->template->content = View::forge('login/index', $data);
    }
    
    public function action_view($id = null) {
        is_null($id) and Response::redirect('login');
        
        if ( ! $data['login'] = Model_User::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find login # '.$id);
            Response::redirect('login');
        }
        
        $this->template->title = "Login";
        $this->template->content = View::forge('login/view', $data);
    }
    
    public function action_dologin() {
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
                        
                        //echo '<pre>'; print_r($data); //debug test
                        //echo $data['users'][0]->username.' '.$data['users'][0]->password.'<br/>'; //debug test
                        
                        if ($data['users'][0]->is_admin == 1) {
                            Session::set('is_admin', '1');
                        }
                        else {
                            Session::set('is_admin', '0');
                        }
                        
                        //echo Session::get('username').' '.Session::get('is_admin').'<br/>'; //debug test
                        
                        Session::set('is_login', 'True');
                        
                        Response::redirect('message');
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
        $this->template->content = View::forge('login/dologin');
    }
    
    public function action_dologout() {
        Session::destroy();
        
        $this->template->title = "Logout Successfully, Please Login Again";
        $this->template->content = View::forge('login/dologin');
        //$this->template->content = View::forge('login/index');
    }
    
    public function action_createuser() {
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
        $this->template->content = View::forge('login/createuser');
    }
    
    public function action_edit($id = null)
    {
        is_null($id) and Response::redirect('login');
        
        if ( ! $login = Model_User::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find login # '.$id);
            Response::redirect('login');
        }
        
        $val = Model_User::validate('edit');
        
        if ($val->run()) {
            $login->username = Input::post('username');
            $login->password = Input::post('password');
            
            if ($login->save()) {
                Session::set_flash('success', 'Updated login # ' . $id);
                
                Response::redirect('login');
            } else {
                Session::set_flash('error', 'Could not update login # ' . $id);
            }
        } else {
            if (Input::method() == 'POST') {
                $login->username = $val->validated('username');
                $login->password = $val->validated('password');
                
                Session::set_flash('error', $val->error());
            }
            
            $this->template->set_global('login', $login, false);
        }
        
        $this->template->title = "Logins";
        $this->template->content = View::forge('login/edit');
    }
    
    public function action_delete($id = null)
    {
        is_null($id) and Response::redirect('login');
        
        if ($login = Model_User::find_by_pk($id)) {
            $login->delete();
            
            Session::set_flash('success', 'Deleted login # '.$id);
        } else {
            Session::set_flash('error', 'Could not delete login # '.$id);
        }
        
        Response::redirect('login');
    }
}
