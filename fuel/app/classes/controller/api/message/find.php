<?php
class Controller_Api_Message_Find extends Controller_Api {
    public function get_list() {
        $request = Input::all();
        
        $sort = strtoupper(Input::get('sort'));
        $limit = strtolower(Input::get('limit'));
        
        //$offset = (int) Input::get('offset');
        
        $query_select = null;
        
        if ($sort == 'DESC') {
            $query_select = 'SELECT id, time, username, title, message
                                       FROM messages
                                       ORDER BY id DESC';
        } else {
            $query_select = 'SELECT id, time, username, title, message
                                       FROM messages';
        }
        
        $result_find = \DB::query($query_select)->execute()->as_array();
        
        $data = null;
        
        if (isset($result_find)) {
            if ($limit == 'all') {
                $data = $result_find;
            } else {
                $limit = (int) $limit;
                
                $count_records = 0;
                
                foreach ($result_find as $record) {
                    $data[] = $record;
                    
                    $count_records++;
                    
                    if ($count_records == $limit) {
                        break;
                    }
                }
            }
        }
        
        $body = null;
        
        if (isset($result_find)) {
            $body = array(
                'success' => 'true',
                'msg' => 'The Messages Are Found',
                'data' => $data
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
