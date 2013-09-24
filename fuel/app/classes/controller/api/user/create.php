<?php
class Controller_Api_User_Create extends Controller_Api {
    public function post_new() {
        $request = Input::all();
        
        $new_user = Model_User::check_user(
            Input::post('username'),
            Input::post('password'),
            'IN USE'
        );
        
        $body = null;
        
        if ($new_user == 'ERROR') {
            $body = array(
                "success" => "false",
                "msg" => "Your Username Should Be At Least \"1\" Character, And Your Password Should Be At Least \"4\" Characters",
                "data" => $request
            );
        } else if ($new_user == 'IN USE') {
            $body = array(
                "success" => "false",
                "msg" => "Your Username Is Already In Use",
                "data" => $request
            );
        } else {
            $new_user->save();
            
            $body = array(
                "success" => "true",
                "msg" => "The New User Is Created",
                "data" => $new_user
            );
        }
        
        $this->response($body);
    }
}
