<?php
class Controller_Api_Comment_Free extends Controller_Api {
    public function delete_remove() {
        $request = Input::all();
        
        $is_removed = false;
        
        $id = Input::delete('id');
        
        if ($remove_comment = Model_Comment::find_by_pk($id)) {
            $remove_comment->delete();
            
            $is_removed = true;
        }
        
        $body = null;
        
        if ($is_removed) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Removed",
                "data" => $remove_comment
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Remove The Comment",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
