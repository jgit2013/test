<?php
class Controller_Api_Comment_Edit extends Controller_Api {
    public function put_update() {
        $request = Input::all();
        
        $is_updated = false;
        
        $id = Input::put('id');
        
        $after_comment = Input::put('comment');
        
        $before_comment = null;
        
        if ($update_comment = Model_Comment::find_by_pk($id)) {
            $before_comment = $update_comment->comment;
            
            if (($after_comment != null) && ($after_comment != $before_comment)) {
                $update_comment->comment = $after_comment;
                
                if ($update_comment->save()) {
                    $is_updated = true;
                }
            }
        }
        
        $body = null;
        
        if ($is_updated) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Updated",
                "data" => $update_comment
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
