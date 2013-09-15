<?php
class Controller_Api_Put extends Controller_Api {
    public function put_message() {
        $is_edited = false;
        
        $id = Input::put('id');
        
        $username = Input::put('username');
        $after_title = Input::put('title');
        $after_message = Input::put('message');
        
        $before_title = null;
        $before_message = null;
        
        if ($found_message = Model_Message::find_by_pk($id)) {
            $before_title = $found_message->title;
            $before_message = $found_message->message;
            
            $val = Model_Message::validate('edit_message');
            
            if ($val->run()) {
                $found_message->title = $after_title;
                $found_message->message = $after_message;
                
                $found_message->save();
                
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
                "msg" => "The Message Is Edited",
                "data" => $found_message
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
    
    public function put_comment() {
        $is_edited = false;
        
        $id = Input::put('id');
        
        $comment = Input::put('comment');
        
        $before_comment = null;
        
        if ($found_comment = Model_Comment::find_by_pk($id)) {
            $before_comment = $found_comment->comment;
            
            $val = Model_Comment::validate('edit_comment');
            
            if ($val->run()) {
                $found_comment->comment = $comment;
                
                if ($found_comment->save()) {
                    $is_edited = true;
                }
            }
        }
        
        $body = null;
        
        if ($is_edited) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Edited",
                "data" => $found_comment
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
