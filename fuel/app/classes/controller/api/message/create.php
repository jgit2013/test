<?php
class Controller_Api_Message_Create extends Controller_Api {
    public function post_new() {
        $request = Input::all();
        
        $is_created = false;
        
        $username = Input::post('username');
        $title = Input::post('title');
        $message = Input::post('message');
        
        $val = Model_Message::validate('new');
        
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
                
                $is_created = true;
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
        
        if ($is_created) {
            $body = array(
                "success" => "true",
                "msg" => "The New Message Is Created",
                "data" => $new_message
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Title</span> And
                                 <span class='muted'>Message</span> Should Be At Least
                                 <span class='muted'>\"1\"</span> Character",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
