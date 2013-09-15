<?php
class Controller_Api_Delete extends Controller_Api {
    public function delete_user() {
        $is_deleted = false;
        
        $id = Input::delete('id');
        
        if ($found_user = Model_User::find_by_pk($id)) {
            $found_user->delete();
            
            $is_deleted = true;
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The User Is Deleted",
                "data" => $found_user
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The User",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
    
    public function delete_message() {
        $is_deleted = false;
        
        $id = Input::delete('id');
        
        $username = Input::delete('username');
        
        if ($found_message = Model_Message::find_by_pk($id)) {
            $is_log_save_successfully = Model_MessageLog::save_log(
                $username,
                'D',
                $found_message->title,
                '',
                $found_message->message,
                '',
                '1'
            );
            
            if ($is_log_save_successfully) {
                $found_message_comments = Model_Comment::find(
                    array(
                        'select' => array(
                            'id',
                            'message_id'
                        ),
                        'where' => array(
                            array('message_id', '=', $id),
                        )
                    )
                );
                
                if ($found_message_comments) {
                    foreach ($found_message_comments as $found_message_comment) {
                        $found_message_comment->delete();
                    }
                }
                
                $found_message->delete();
                
                $is_deleted = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'D',
                    $found_message->title,
                    '',
                    $found_message->message,
                    '',
                    '0'
                );
            }
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Deleted",
                "data" => $found_message
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The Message",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
    
    public function delete_comment() {
        $is_deleted = false;
        
        $id = Input::delete('id');
        
        if ($found_comment = Model_Comment::find_by_pk($id)) {
            $found_comment->delete();
            
            $is_deleted = true;
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Deleted",
                "data" => $found_comment
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The Comment",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
}
