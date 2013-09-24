<?php
class Controller_Api_Message_Free extends Controller_Api {
    public function delete_remove() {
        $request = Input::all();
        
        $is_removed = false;
        
        $id = Input::delete('id');
        
        $username = Input::delete('username');
        
        if ($remove_message = Model_Message::find_by_pk($id)) {
            $is_log_save_successfully = Model_MessageLog::save_log(
                $username,
                'D',
                $remove_message->title,
                '',
                $remove_message->message,
                '',
                '1'
            );
            
            if ($is_log_save_successfully) {
                $remove_message_comments = Model_Comment::find(
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
                
                if ($remove_message_comments) {
                    foreach ($remove_message_comments as $remove_message_comment) {
                        $remove_message_comment->delete();
                    }
                }
                
                $remove_message->delete();
                
                $is_removed = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'D',
                    $remove_message->title,
                    '',
                    $remove_message->message,
                    '',
                    '0'
                );
            }
        }
        
        $body = null;
        
        if ($is_removed) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Removed",
                "data" => $remove_message
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Remove The Message",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
