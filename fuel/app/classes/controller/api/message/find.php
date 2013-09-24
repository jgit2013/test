<?php
class Controller_Api_Message_Find extends Controller_Api {
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
        
        $found_messages = Model_Message::find($conditions);
        
        $body = null;
        
        if (isset($found_messages)) {
            $body = array(
                "success" => "true",
                "msg" => "The Messages Are Found",
                "data" => $found_messages
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Messages",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
