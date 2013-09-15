<?php
class Controller_Api_Get extends Controller_Api {
    public function get_find_user_logs() {
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
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function get_find_message_logs() {
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
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function get_find_users() {
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
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function get_find_messages() {
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
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function get_find_comments() {
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
        
        $found_comments = Model_Comment::find($conditions);
        
        $body = null;
        
        if (isset($found_comments)) {
            $body = array(
                "success" => "true",
                "msg" => "The Comments Are Found",
                "data" => $found_comments
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Comments",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
}
