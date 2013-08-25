<?php
/**
 * Common Controller class
 *
 * 一般使用者與管理員登入留言版後進行所有留言相關操作的Controller
 *
 * @author    J
 */
class Controller_Common extends \Controller_Template
{
    /**
     * 將頁面導向views/message/view_message.php，內容為顯示在留言版上的單一訊息
     */
    public function action_view_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
    
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        if ( ! $found_message = Model_Message::find_by_pk($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
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
    
            $view = View::forge('common/view_message');
    
            if (isset($found_message_comments)) {
                $view->set('found_message_comments', $found_message_comments, true);
            }
    
            $view->set_global('found_message', $found_message, true);
    
            $this->template->title = "Message >> View";
            $this->template->content = $view;
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
    
        $error_message = null;
    
        if (Input::method() == 'POST') {
            $val = Model_Message::validate('add_message');
    
            if ($val->run()) {
                $new_message = Model_Message::forge(array(
                    'username' => Session::get('username'),
                    'title' => Input::post('title'),
                    'message' => Input::post('message'),
                ));
    
                if ($new_message && $new_message->save()) {
                    Model_MessageLog::save_log(
                        Session::get('username'),
                        'C',
                        '',
                        Input::post('title'),
                        '',
                        Input::post('message'),
                        '1'
                    );
                    
                    if ((Session::get('is_admin') == '1')) {
                        Response::redirect('admin');
                    } else {
                        Response::redirect('user');
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
    
                $error_message = "Your <span class='muted'>Title</span> And
                                       <span class='muted'>Message</span> Should Be At Least
                                       <span class='muted'>\"1\"</span> Character";
            }
        }
    
        $view = View::forge('common/add_message');
    
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
    
        $this->template->title = "Message >> Add";
        $this->template->content = $view;
    }
    
    /**
     * 將頁面導向views/message/edit_message.php，
     * 修改一則該使用者自己建立的訊息
     */
    public function action_edit_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        $before_title = null;
        $before_message = null;
    
        if ( ! $found_message = Model_Message::find_by_pk($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        } else {
            $before_title = $found_message->title;
            $before_message = $found_message->message;
        }
    
        $error_message = null;
    
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
                    
                    if ((Session::get('is_admin') == '1')) {
                        Response::redirect('admin');
                    } else {
                        Response::redirect('user');
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
    
                /* if (Input::method() == 'POST') {
                 $found_message->title = $val->validated('title');
                $found_message->message = $val->validated('message');
                } */
    
                $error_message = "Your <span class='muted'>Title</span> And
                                       <span class='muted'>Message</span> Should Be At Least
                                       <span class='muted'>\"1\"</span> Character";
            }
        }
    
        $view = View::forge('common/edit_message');
    
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
    
        $view->set_global('found_message', $found_message, true);
    
        $this->template->title = "Message >> Edit";
        $this->template->content = $view;
    }
    
    /**
     * 刪除一則該使用者自己建立的訊息與對應該訊息的所有評論
     */
    public function action_delete_message($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        if ($found_message = Model_Message::find_by_pk($id)) {
            $is_log_save_successfully = Model_MessageLog::save_log(
                Session::get('username'),
                'D',
                $found_message->title,
                '',
                $found_message->message,
                '',
                '1'
            );
    
            if ( ! $is_log_save_successfully) {
                Model_MessageLog::save_log(
                    Session::get('username'),
                    'D',
                    $found_message->title,
                    '',
                    $found_message->message,
                    '',
                    '0'
                );
            }
    
            $found_message_comments = Model_Comment::find(array(
                'select' => array('id', 'message_id'),
                'where' => array(
                    array('message_id', '=', $found_message->id),
                ),
            ));
            
            if (isset($found_message_comments)) {
                foreach ($found_message_comments as $found_message_comment) {
                    $found_message_comment->delete();
                }
            }
            
            $found_message->delete();
        }
        
        if ((Session::get('is_admin') == '1')) {
            Response::redirect('admin');
        } else {
            Response::redirect('user');
        }
    }
    
    /**
     * 將頁面導向views/message/add_comment.php，
     * 若未建立新評論時顯示建立新評論的頁面，
     * 若使用者建立的評論長度不足時顯示錯誤訊息
     */
    public function action_add_comment($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        if (Input::method() == 'POST') {
            $val = Model_Comment::validate('add_comment');
    
            if ($val->run()) {
                $message = Model_Comment::forge(array(
                    'message_id' => $id,
                    'username' => Session::get('username'),
                    'comment' => Input::post('comment'),
                ));
    
                if ($message && $message->save()) {
                    //Model_CommentLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '1');
    
    
    
                    Response::redirect('common/view_message/'.$id);
                } else {
    
                }
            } else {
                //Model_CommentLog::save_log(Session::get('username'), 'C', '', Input::post('title'), '', Input::post('message'), '0');
    
                $error_message = "Your <span class='muted'>Comment</span> Should Be At Least
                                       <span class='muted'>\"1\"</span> Character";
            }
        }
    
        $view = View::forge('common/add_comment');
    
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
    
        $view->set_global('message_id', $id, true);
    
        $this->template->title = "Message >> Add Comment";
        $this->template->content = $view;
    }
    
    /**
     * 將頁面導向views/message/edit_comment.php，
     * 修改一則該使用者自己建立的評論
     */
    public function action_edit_comment($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        $before_comment = null;
    
        if ( ! $found_comment = Model_Comment::find_by_pk($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        } else {
            $before_comment = $found_comment->comment;
        }
    
        $error_message = null;
    
        if (Input::method() == 'POST') {
            $val = Model_Comment::validate('edit_comment');
    
            if ($val->run()) {
                $found_comment->comment = Input::post('comment');
    
                if ($found_comment->save()) {
                    /* Model_CommentLog::save_log(
                     Session::get('username'),
                        'U',
                        $before_title,
                        Input::post('title'),
                        $before_message,
                        Input::post('message'),
                        '1'
                    ); */
    
    
    
                    Response::redirect('common/view_message/'.$found_comment->message_id);
                } else {
    
                }
            } else {
                /* Model_CommentLog::save_log(
                 Session::get('username'),
                    'U',
                    $before_title,
                    Input::post('title'),
                    $before_message,
                    Input::post('message'),
                    '0'
                ); */
    
                $error_message = "Your <span class='muted'>Comment</span> Should Be At Least
                                       <span class='muted'>\"1\"</span> Character";
            }
        }
    
        $view = View::forge('common/edit_comment');
    
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
    
        $view->set_global('found_comment', $found_comment, true);
    
        $this->template->title = "Message >> Edit Comment";
        $this->template->content = $view;
    }
    
    /**
     * 刪除一則該使用者自己建立的評論
     */
    public function action_delete_comment($id = null)
    {
        is_null(Session::get('is_sign_in')) and Response::redirect('404');
        
        if (is_null($id)) {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        }
    
        if ($found_comment = Model_Comment::find_by_pk($id)) {
            /* $is_log_save_successfully = Model_CommentLog::save_log(
             Session::get('username'),
                'D',
                $message->title,
                '',
                $message->message,
                '',
                '1'
            ); */
    
            /* if ( ! $is_log_save_successfully) {
             Model_CommentLog::save_log(
                 Session::get('username'),
                 'D',
                 $message->title,
                 '',
                 $message->message,
                 '',
                 '0'
             );
            } */
    
            $found_comment->delete();
    
    
        } else {
    
        }
    
        Response::redirect('common/view_message/'.$found_comment->message_id);
    }
}