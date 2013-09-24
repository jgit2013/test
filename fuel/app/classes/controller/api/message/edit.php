<?php
class Controller_Api_Message_Edit extends Controller_Api {
    public function put_update() {
        $request = Input::all();
        
        $is_edited = false;
        
        $id = Input::put('id');
        
        $username = Input::put('username');
        $after_title = Input::put('title');
        $after_message = Input::put('message');
        
        $before_title = null;
        $before_message = null;
        
        if ($update_message = Model_Message::find_by_pk($id)) {
            $before_title = $update_message->title;
            $before_message = $update_message->message;
            
            if ((($after_title != null) && ($after_message != null))
                 && (($after_title != $before_title) || ($after_message != $before_message))) {
                
                $update_message->title = $after_title;
                $update_message->message = $after_message;
                
                $update_message->save();
                
                Model_MessageLog::save_log(
                    $username,
                    'U',
                    $before_title,
                    $after_title,
                    $before_message,
                    $after_message,
                    '1'
                );
                
                $is_edited = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'U',
                    $before_title,
                    $after_title,
                    $before_message,
                    $after_message,
                    '0'
                );
            }
        }
        
        $body = null;
        
        if ($is_edited) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Updated",
                "data" => $update_message
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
