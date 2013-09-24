<?php
class Controller_Api_User_Log extends Controller_Api {
    public function get_search() {
        $request = Input::all();
        
        $conditions = array();
        
        if (Input::get('select') != null) {
            $conditions['select'] = Input::get('select');
        }
        
        if (Input::get('where') != null) {
            $conditions['where'] = Input::get('where');
        }
        
        if (Input::get('order_by') != null) {
            $conditions['order_by'] = Input::get('order_by');
        }
        
        $user_logs = Model_UserLog::find($conditions);
        
        $body = null;
        
        if (isset($user_logs)) {
            $body = array(
                "success" => "true",
                "msg" => "The User Logs Are Found",
                "data" => $user_logs
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The User Logs",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
