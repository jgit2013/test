<?php
class Controller_Api extends \Controller_Rest
{
    protected $format = 'json';
    
    public function post_sign_in()
    {
        $found_user = Model_User::check_user(
            Input::post('username'),
            Input::post('password'),
            'IN TABLE'
        );
        
        $body = null;
        
        if (($found_user == 'ERROR') || ($found_user == 'NOT IN TABLE')) {
            $body = array(
                "success" => "false",
                "msg" => "Incorrect Username Or Password",
                "data" => null
            );
        } else {
            $body = array(
                "success" => "true",
                "msg" => "User Found",
                "data" => $found_user
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_sign_up()
    {
        $new_user = Model_User::check_user(
            Input::post('username'),
            Input::post('password'),
            'IN USE'
        );
        
        $body = null;
        
        if ($new_user == 'ERROR') {
            $body = array(
                "success" => "false",
                "msg" => "Your Username Should Be At Least \"1\" Character, And Your Password Should Be At Least \"4\" Characters",
                "data" => null
            );
        } else if ($new_user == 'IN USE') {
            $body = array(
                "success" => "false",
                "msg" => "Your Username Is Already In Use",
                "data" => null
            );
        } else {
            $new_user->save();
            
            $body = array(
                "success" => "true",
                "msg" => "Create The User",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_delete_user()
    {
        $is_deleted = false;
        
        $id = Input::post('id');
        
        if ($found_user = Model_User::find_by_pk($id)) {
            $found_user->delete();
            
            $is_deleted = true;
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The User Is Deleted",
                "data" => $found_user
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The User",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_find_user_logs()
    {
        $conditions = array();
        
        if (Input::post('select') != null) {
            $conditions['select'] = Input::post('select');
        }
        
        if (Input::post('where') != null) {
            $conditions['where'] = Input::post('where');
        }
        
        if (Input::post('order_by') != null) {
            $conditions['order_by'] = Input::post('order_by');
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
    
    public function post_add_message()
    {
        $is_saved = false;
        
        $username = Input::post('username');
        $title = Input::post('title');
        $message = Input::post('message');
        
        $val = Model_Message::validate('add_message');
        
        if ($val->run()) {
            $new_message = Model_Message::forge(array(
                'username' => $username,
                'title' => $title,
                'message' => $message
            ));
            
            if ($new_message && $new_message->save()) {
                Model_MessageLog::save_log(
                    $username,
                    'C',
                    '',
                    $title,
                    '',
                    $message,
                    '1'
                );
                
                $is_saved = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'C',
                    '',
                    $title,
                    '',
                    $message,
                    '0'
                );
            }
        } else {
            Model_MessageLog::save_log(
                $username,
                'C',
                '',
                $title,
                '',
                $message,
                '0'
            );
        }
        
        $body = null;
        
        if ($is_saved) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Saved",
                "data" => null
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Title</span> And
                                  <span class='muted'>Message</span> Should Be At Least
                                  <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_edit_message()
    {
        $is_edited = false;
        
        $id = Input::post('id');
        
        $username = Input::post('username');
        $after_title = Input::post('title');
        $after_message = Input::post('message');
        
        $before_title = null;
        $before_message = null;
        
        if ($found_message = Model_Message::find_by_pk($id)) {
            $before_title = $found_message->title;
            $before_message = $found_message->message;
            
            $val = Model_Message::validate('edit_message');
            
            if ($val->run()) {
                $found_message->title = $after_title;
                $found_message->message = $after_message;
                
                $found_message->save();
                
                Model_MessageLog::save_log(
                    $username,
                    'U',
                    $before_title,
                    $after_title,
                    $before_message,
                    $after_message,
                    '1'
                );
                
                $is_edited = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'U',
                    $before_title,
                    $after_title,
                    $before_message,
                    $after_message,
                    '0'
                );
            }
        }
        
        $body = null;
        
        if ($is_edited) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Edited",
                "data" => $found_message
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Title</span> And
                                  <span class='muted'>Message</span> Should Be At Least
                                  <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_find_message_logs()
    {
        $conditions = array();
        
        if (Input::post('select') != null) {
            $conditions['select'] = Input::post('select');
        }
        
        if (Input::post('where') != null) {
            $conditions['where'] = Input::post('where');
        }
        
        if (Input::post('order_by') != null) {
            $conditions['order_by'] = Input::post('order_by');
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
    
    public function post_delete_message()
    {
        $is_deleted = false;
        
        $id = Input::post('id');
        
        $username = Input::post('username');
        
        if ($found_message = Model_Message::find_by_pk($id)) {
            $is_log_save_successfully = Model_MessageLog::save_log(
                $username,
                'D',
                $found_message->title,
                '',
                $found_message->message,
                '',
                '1'
            );
            
            if ($is_log_save_successfully) {
                $found_message_comments = Model_Comment::find(
                    array(
                        'select' => array(
                            'id',
                            'message_id'
                        ),
                        'where' => array(
                            array('message_id', '=', $id),
                        )
                    )
                );
                
                if ($found_message_comments) {
                    foreach ($found_message_comments as $found_message_comment) {
                        $found_message_comment->delete();
                    }
                }
                
                $found_message->delete();
                
                $is_deleted = true;
            } else {
                Model_MessageLog::save_log(
                    $username,
                    'D',
                    $found_message->title,
                    '',
                    $found_message->message,
                    '',
                    '0'
                );
            }
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The Message Is Deleted",
                "data" => $found_message
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The Message",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_add_comment()
    {
        $is_saved = false;
        
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
                $is_saved = true;
            }
        }
        
        $body = null;
        
        if ($is_saved) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Saved",
                "data" => null
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Comment</span> Should Be At Least
                                  <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_edit_comment()
    {
        $is_edited = false;
        
        $id = Input::post('id');
        
        $comment = Input::post('comment');
        
        $before_comment = null;
        
        if ($found_comment = Model_Comment::find_by_pk($id)) {
            $before_comment = $found_comment->comment;
            
            $val = Model_Comment::validate('edit_comment');
            
            if ($val->run()) {
                $found_comment->comment = $comment;
                
                if ($found_comment->save()) {
                    $is_edited = true;
                }
            }
        }
        
        $body = null;
        
        if ($is_edited) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Edited",
                "data" => $found_comment
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Your <span class='muted'>Comment</span> Should Be At Least
                                  <span class='muted'>\"1\"</span> Character",
                "data" => null
            );
        }
        
        $this->response($body, 200);
    }
    
    public function post_delete_comment()
    {
        $is_deleted = false;
        
        $id = Input::post('id');
        
        if ($found_comment = Model_Comment::find_by_pk($id)) {
            $found_comment->delete();
            
            $is_deleted = true;
        }
        
        $body = null;
        
        if ($is_deleted) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Deleted",
                "data" => $found_comment
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Delete The Comment",
                "data" => $id
            );
        }
        
        $this->response($body, 200);
    }
    
    public function get_find_users()
    {
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
    
    public function get_find_messages()
    {
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
    
    public function get_find_comments()
    {
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
