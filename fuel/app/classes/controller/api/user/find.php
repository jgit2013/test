<?php
class Controller_Api_User_Find extends Controller_Api {
    /* public function get_one() {
        $request = Input::all();
        
        $found_user = null;
        
        if (isset($request['id']) && ($request['id'] > 0)) {
            $found_user = Model_User::find_by_pk($request['id']);
        }
        
        $body = null;
        
        if (isset($found_user)) {
            $body = array(
                "success" => "true",
                "msg" => "The User Is Found",
                "data" => $found_user
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The User",
                "data" => $request
            );
        }
        
        $this->response($body);
    } */
    
    /* public function get_list() {
        $request = Input::all();
        
        $conditions = array('id' => 'asc');
        
        if (Input::get('order_by') != null) {
            $conditions['order_by'] = Input::get('order_by');
        }
        
        $found_users = Model_User::find($conditions);
        
        $body = null;
        
        if (isset($found_users)) {
            $body = array(
                "success" => "true",
                "msg" => "The Users Are Found",
                "data" => $found_users
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Users",
                "data" => $request
            );
        }
        
        $this->response($body);
    } */
    
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
        
        $found_users = Model_User::find($conditions);
        
        $body = null;
        
        if (isset($found_users)) {
            $body = array(
                "success" => "true",
                "msg" => "The Users Are Found",
                "data" => $found_users
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Users",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
