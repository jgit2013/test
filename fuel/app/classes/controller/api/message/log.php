<?php
class Controller_Api_Message_Log extends Controller_Api {
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
        
        $message_logs = Model_MessageLog::find($conditions);
        
        $body = null;
        
        if (isset($message_logs)) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Logs Are Found",
                "data" => $message_logs
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Message Logs",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
