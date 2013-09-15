<?php
class Controller_Api_Post extends Controller_Api {
    public function post_sign_in() {
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
                "data" => null
            );
        } else {
            $body = array(
                "success" => "true",
                "msg" => "User Found",
                "data" => $found_user
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_sign_up() {
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
                "data" => null
            );
        } else if ($new_user == 'IN USE') {
            $body = array(
                "success" => "false",
                "msg" => "Your Username Is Already In Use",
                "data" => null
            );
        } else {
            $new_user->save();
            
            $body = array(
                "success" => "true",
                "msg" => "Create The User",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_create_message() {
        $is_saved = false;
        
        $username = Input::post('username');
        $title = Input::post('title');
        $message = Input::post('message');
        
        $val = Model_Message::validate('add_message');
        
        if ($val->run()) {
            $new_message = Model_Message::forge(
                array(
                    'username' => $username,
                    'title' => $title,
                    'message' => $message
                )
            );
            
            if ($new_message && $new_message->save()) {
                Model_MessageLog::save_log(
                    $username,
                    'C',
                    '',
                    $title,
                    '',
                    $message,
                    '1'
                );
                
                $is_saved = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'C',
                    '',
                    $title,
                    '',
                    $message,
                    '0'
                );
            }
        } else {
            Model_MessageLog::save_log(
                $username,
                'C',
                '',
                $title,
                '',
                $message,
                '0'
            );
        }
        
        $body = null;
        
        if ($is_saved) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Saved",
                "data" => null
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Title</span> And
                          <span class='muted'>Message</span> Should Be At Least
                          <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_create_comment() {
        $is_saved = false;
        
        $message_id = Input::post('id');
        $username = Input::post('username');
        $comment = Input::post('comment');
        
        $val = Model_Comment::validate('add_comment');
        
        if ($val->run()) {
            $new_comment = Model_Comment::forge(
                array(
                    'message_id' => $message_id,
                    'username' => $username,
                    'comment' => $comment
                )
            );
            
            if ($new_comment && $new_comment->save()) {
                $is_saved = true;
            }
        }
        
        $body = null;
        
        if ($is_saved) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Saved",
                "data" => null
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Comment</span> Should Be At Least
                          <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
}
