<?php
class Controller_Api_Comment_Find extends Controller_Api {
    public function get_one() {
        $request = Input::all();
        
        $comment_id = Input::get('comment_id');
        
        $found_comment = null;
        
        if ($comment_id != null) {
            $found_comment = Model_Comment::find(
                array(
                    'select' => array(
                        'id',
                        'time',
                        'message_id',
                        'username',
                        'comment'
                    ),
                    'where' => array(
                        array('id', '=', $comment_id)
                    )
                )
            );
        }
        
        $body = null;
        
        if (isset($found_comment)) {
            $body = array(
                "success" => "true",
                "msg" => "The Comment Is Found",
                "data" => $found_comments
            );
        } else {
            $body = array(
                "success" => "false",
                "msg" => "Can't Find The Comment",
                "data" => $request
            );
        }
        
        $this->response($body);
    }
    
    public function get_search() {
        $request = Input::all();
        
        /* $conditions = Input::get('conditions');
        $sort_by = Input::get('sort_by', 'asc'); */
        
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
        
        /* $found_comments = Model_Comment::find(
            array(
                'select' => array(
                    'id',
                    'time',
                    'message_id',
                    'username',
                    'comment'
                ),
                'where' => array(
                    
                ),
                'order_by' => array('id' => $sort_by)
            )
        ); */
        
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
                "data" => $request
            );
        }
        
        $this->response($body);
    }
}
