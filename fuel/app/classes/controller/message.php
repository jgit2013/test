<?php
/**
 * Message Controller class
 *
 * 一般使用者登入後留言版操作頁面的Controller
 *
 * @author    J
 */
class Controller_Message extends \Controller_Template
{
    /**
     * 將頁面導向views/message/index.php，
     * 內容為留言版的頁面，
     * 除了可以建立新的訊息外，也可選擇觀看自己或其他人建立的訊息，
     * 且修改或刪除自己留的訊息 ，而訊息排列方式會由最新的訊息排到最舊的訊息
     */
    public function action_index()
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        $messages = Model_Message::find(array(
            'select' => array(
                'id',
                'time',
                'username',
                'title',
                'message'
            ),
            'order_by' => array('id' => 'desc'),
        )); //find all the records in the table and order by id desc
        
        //$data['messages'] = Model_Message::find_all(); //find all the records in the table
        
        $comments = array();
        
        foreach ($messages as $message) {
            $results = DB::select()->from('comments')->where('message_id', $message->id)->execute();
            
            $comments[$message->id] = count($results);
        }
        
        $view = View::forge('message/index');
        
        if (isset($messages)) {
            $view->set('messages', $messages, true);
            $view->set('comments', $comments, true);
        }
        
        $this->template->title = "Hello, \"".Session::get('username')."\"";
        $this->template->content = $view;
        
        /* $this->template->title = "Hello, \"".Session::get('username')."\"";
        $this->template->content = View::forge('message/index', $data); */
    }
    
    /**
     * 將頁面導向views/message/view_message.php，內容為顯示在留言版上的單一訊息
     */
    public function action_view_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        if ( ! $found_message = Model_Message::find_by_pk($id)) {
            Response::redirect('message');
        } else {
            $found_message_comments = Model_Comment::find(array(
                'select' => array(
                    'id',
                    'time',
                    'message_id',
                    'username',
                    'comment'
                ),
                'where' => array(
                    array('message_id', '=', $found_message->id),
                ),
            ));
            
            /* echo '<pre>'; print_r($found_message_comments);
            
            exit; */
            
            $view = View::forge('message/view_message');
            
            $view->set_global('found_message', $found_message, true);
            
            if (isset($found_message_comments)) {
                $view->set_global('found_message_comments', $found_message_comments, true);
            }
            
            $this->template->title = "Message >> View";
            $this->template->content = $view;
            
            /* $this->template->set_global('found_message', $found_message, false);
            
            $this->template->title = "Message >> View";
            $this->template->content = View::forge('message/view'); */
        }
    }
    
    /**
     * 將頁面導向views/message/add_message.php，
     * 若未建立新訊息時顯示建立新訊息的頁面，
     * 若使用者建立的標題或訊息長度不足時顯示錯誤訊息
     */
    public function action_add_message()
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        $is_title_or_message_too_short = false;
        
        if (Input::method() == 'POST') {
            $val = Model_Message::validate('add_message');
            
            if ($val->run()) {
                $message = Model_Message::forge(array(
                    'username' => Session::get('username'),
                    'title' => Input::post('title'),
                    'message' => Input::post('message'),
                ));
                
                if ($message && $message->save()) {
                    Model_MessageLog::save_log(
                        Session::get('username'),
                        'C',
                        '',
                        Input::post('title'),
                        '',
                        Input::post('message'),
                        '1'
                    );
                    
                    Session::set_flash('success', 'Added message # '.$message->id.'.');
                    
                    Response::redirect('message');
                } else {
                    Session::set_flash('error', 'Could not save message.');
                }
            } else {
                Model_MessageLog::save_log(
                    Session::get('username'),
                    'C',
                    '',
                    Input::post('title'),
                    '',
                    Input::post('message'),
                    '0'
                );
                
                $is_title_or_message_too_short = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        if ($is_title_or_message_too_short) {
            $this->template->title = "Message >> Add (Your Title And Message Should Be At Least \"1\" Character)";
            $this->template->content = View::forge('message/add_message');
        } else {
            $this->template->title = "Message >> Add";
            $this->template->content = View::forge('message/add_message');
        }
    }
    
    /**
     * 將頁面導向views/message/edit_message.php，
     * 修改一則該使用者自己建立的訊息
     */
    public function action_edit_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        $before_title = null;
        $before_message = null;
        
        if ( ! $found_message = Model_Message::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find message # '.$id);
            
            Response::redirect('message');
        } else {
            $before_title = $found_message->title;
            $before_message = $found_message->message;
        }
        
        $is_title_or_message_too_short = false;
        
        if (Input::method() == 'POST') {
            $val = Model_Message::validate('edit_message');
            
            if ($val->run()) {
                $found_message->title = Input::post('title');
                $found_message->message = Input::post('message');
                
                if ($found_message->save()) {
                    Model_MessageLog::save_log(
                        Session::get('username'),
                        'U',
                        $before_title,
                        Input::post('title'),
                        $before_message,
                        Input::post('message'),
                        '1'
                    );
                    
                    Session::set_flash('success', 'Updated message # ' . $id);
                    
                    Response::redirect('message');
                } else {
                    Session::set_flash('error', 'Could not update message # ' . $id);
                }
            } else {
                Model_MessageLog::save_log(
                    Session::get('username'),
                    'U',
                    $before_title,
                    Input::post('title'),
                    $before_message,
                    Input::post('message'),
                    '0'
                );

                if (Input::method() == 'POST') {
                    $found_message->title = $val->validated('title');
                    $found_message->message = $val->validated('message');

                    Session::set_flash('error', $val->error());
                }
                
                $is_title_or_message_too_short = true;
            }
        }
        
        $this->template->set_global('found_message', $found_message, false);
        
        if ($is_title_or_message_too_short) {
            $this->template->title = "Message >> Edit (Your Title And Message Should Be At Least \"1\" Character)";
            $this->template->content = View::forge('message/edit_message');
        } else {
            $this->template->title = "Message >> Edit";
            $this->template->content = View::forge('message/edit_message');
        }
    }
    
    /**
     * 刪除一則該使用者自己建立的訊息
     */
    public function action_delete_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        if ($message = Model_Message::find_by_pk($id)) {
            $is_log_save_successfully = Model_MessageLog::save_log(
                Session::get('username'),
                'D',
                $message->title,
                '',
                $message->message,
                '',
                '1'
            );
            
            if ( ! $is_log_save_successfully) {
                Model_MessageLog::save_log(
                    Session::get('username'),
                    'D',
                    $message->title,
                    '',
                    $message->message,
                    '',
                    '0'
                );
            }
            
            $message->delete();
            
            Session::set_flash('success', 'Deleted message # '.$id);
        } else {
            Session::set_flash('error', 'Could not delete message # '.$id);
        }
        
        Response::redirect('message');
    }
    
    /**
     * 將頁面導向views/message/add_comment.php，
     * 若未建立新訊息時顯示建立新訊息的頁面，
     * 若使用者建立的標題或訊息長度不足時顯示錯誤訊息
     */
    public function action_add_comment($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        $is_comment_too_short = false;
        
        if (Input::method() == 'POST') {
            $val = Model_Comment::validate('add_comment');
            
            /* echo '<pre>'; print_r($val);
            
            exit; */
            
            if ($val->run()) {
                $message = Model_Comment::forge(array(
                    'message_id' => $id,
                    'username' => Session::get('username'),
                    'comment' => Input::post('comment'),
                ));
                
                if ($message && $message->save()) {
                    //Model_CommentLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '1');
                    
                    Session::set_flash('success', 'Added message # '.$message->id.'.');
                    
                    Response::redirect('message/view_message/'.$id);
                } else {
                    Session::set_flash('error', 'Could not save message.');
                }
            } else {
                //Model_CommentLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '0');
                
                $is_comment_too_short = true;
                
                Session::set_flash('error', $val->error());
            }
        }
        
        $this->template->set_global('message_id', $id, true);
        
        if ($is_comment_too_short) {
            $this->template->title = "Message >> Add Comment (Your Comment Should Be At Least \"1\" Character)";
            $this->template->content = View::forge('message/add_comment');
        } else {
            $this->template->title = "Message >> Add Comment";
            $this->template->content = View::forge('message/add_comment');
        }
    }
}
