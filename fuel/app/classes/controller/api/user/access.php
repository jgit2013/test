<?php
class Controller_Api_User_Access extends Controller_Api {
    public function post_sign_in() {
        $request = Input::all();
        
        $found_user = Model_User::check_user(
            Input::post('username'),
            Input::post('password'),
            'IN TABLE'
        );
        
        $body = null;
        
        if (($found_user == 'ERROR') || ($found_user == 'NOT IN TABLE')) {
            $body = array(
                "success" => "false",
                "msg" => "Incorrect Username Or Password",
                "data" => $request
            );
        } else {
            $body = array(
                "success" => "true",
                "msg" => "The User Is Found",
                "data" => $found_user
            );
        }
        
        $this->response($body);
    }
}
