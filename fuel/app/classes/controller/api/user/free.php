<?php
class Controller_Api_User_Free extends Controller_Api {
    public function delete_remove() {
        $request = Input::all();
        
        $is_removed = false;
        
        $id = Input::delete('id');
        
        if ($remove_user = Model_User::find_by_pk($id)) {
            $remove_user->delete();
            
            $is_removed = true;
        }
        
        $body = null;
        
        if ($is_removed) {
            $body = array(
                "success" => "true",
                "msg" => "The User Is Removed",
                "data" => $remove_user
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Remove The User",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
