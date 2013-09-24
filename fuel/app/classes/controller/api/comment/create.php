<?php
class Controller_Api_Comment_Create extends Controller_Api {
    public function post_new() {
        $request = Input::all();
        
        $is_created = false;
        
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
                $is_created = true;
            }
        }
        
        $body = null;
        
        if ($is_created) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Created",
                "data" => $new_comment
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Comment</span> Should Be At Least
                                 <span class='muted'>\"1\"</span> Character",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
