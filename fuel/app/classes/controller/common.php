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
        
        $find_messages_response = Tool_Ask::request_curl(
            'api/find_messages',
            'json',
            'get',
            array(
                'select' => array(
                    'id',
                    'time',
                    'title',
                    'username',
                    'message'
                ),
                'where' => array(
                    array('id', '=', $id)
                )
            )
        );
        
        $find_messages_response_body_json = $find_messages_response->body();
        
        $find_messages_response_body_array = json_decode($find_messages_response_body_json);
        
        if ($find_messages_response_body_array->success == 'false') {
            if ((Session::get('is_admin') == '1')) {
                Response::redirect('admin');
            } else {
                Response::redirect('user');
            }
        } else {
            $found_messages = $find_messages_response_body_array->data;
            
            $find_message_comments_response = Tool_Ask::request_curl(
                'api/find_comments',
                'json',
                'get',
                array(
                    'select' => array(
                        'id',
                        'time',
                        'message_id',
                        'username',
                        'comment'
                    ),
                    'where' => array(
                        array('message_id', '=', $found_messages[0]->id)
                    )
                )
            );
            
            $find_message_comments_response_body_json = $find_message_comments_response->body();
             
            $find_message_comments_response_body_array = json_decode($find_message_comments_response_body_json);
             
            $found_message_comments = $find_message_comments_response_body_array->data;
            
            $view = View::forge('common/view_message');
            
            if (isset($found_message_comments)) {
                $view->set('found_message_comments', $found_message_comments, true);
            }
            
            $view->set_global('found_message', $found_messages[0], true);
            
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
            $response = Tool_Ask::request_curl(
                'api/add_message',
                'json',
                'post',
                array(
                    'username' => Session::get('username'),
                    'title' => Input::post('title'),
                    'message' => Input::post('message')
                )
            );
            
            $body_json = $response->body();
            
            $body_array = json_decode($body_json);
            
            if ($body_array->success == 'false') {
                $error_message = $body_array->msg;
            } else {
                if ((Session::get('is_admin') == '1')) {
                    Response::redirect('admin');
                } else {
                    Response::redirect('user');
                }
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
        
        $find_messages_response = Tool_Ask::request_curl(
            'api/find_messages',
            'json',
            'get',
            array(
                'select' => array(
                    'id',
                    'time',
                    'title',
                    'username',
                    'message'
                ),
                'where' => array(
                    array('id', '=', $id)
                )
            )
        );
        
        $find_messages_response_body_json = $find_messages_response->body();
        
        $find_messages_response_body_array = json_decode($find_messages_response_body_json);
        
        $found_messages = $find_messages_response_body_array->data;
        
        $error_message = null;
        
        if (Input::method() == 'POST') {
            $edit_message_response = Tool_Ask::request_curl(
                'api/edit_message',
                'json',
                'post',
                array(
                    'id' => $id,
                    'username' => Session::get('username'),
                    'title' => Input::post('title'),
                    'message' => Input::post('message')
                )
            );
            
            $edit_message_response_body_json = $edit_message_response->body();
            
            $edit_message_response_body_array = json_decode($edit_message_response_body_json);
            
            if ($edit_message_response_body_array->success == 'false') {
                $error_message = $edit_message_response_body_array->msg;
            } else {
                if ((Session::get('is_admin') == '1')) {
                    Response::redirect('admin');
                } else {
                    Response::redirect('user');
                }
            }
        }
        
        $view = View::forge('common/edit_message');
        
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
        
        $view->set_global('found_message', $found_messages[0], true);
        
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
        
        $response = Tool_Ask::request_curl(
            'api/delete_message',
            'json',
            'post',
            array(
                'id' => $id,
                'username' => Session::get('username')
            )
        );
        
        $response_body_json = $response->body();
        
        $response_body_array = json_decode($response_body_json);
        
        $is_succeed = $response_body_array->success;
        
        if ($is_succeed == 'false') {
            Response::redirect('common/delete_message_fail');
        }
        
        if ((Session::get('is_admin') == '1')) {
            Response::redirect('admin');
        } else {
            Response::redirect('user');
        }
    }
    
    /**
     * 將頁面導向views/common/delete_fail.php
     */
    public function action_delete_message_fail()
    {
        $this->template->title = "Sorry, Can't Delete The Message";
        $this->template->content = View::forge('common/delete_fail');
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
        
        $error_message = null;
        
        if (Input::method() == 'POST') {
            $response = Tool_Ask::request_curl(
                'api/add_comment',
                'json',
                'post',
                array(
                    'id' => $id,
                    'username' => Session::get('username'),
                    'comment' => Input::post('comment')
                )
            );
            
            $body_json = $response->body();
            
            $body_array = json_decode($body_json);
            
            if ($body_array->success == 'false') {
                $error_message = $body_array->msg;
            } else {
                Response::redirect('common/view_message/'.$id);
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
        
        $find_comments_response = Tool_Ask::request_curl(
            'api/find_comments',
            'json',
            'get',
            array(
                'select' => array(
                    'id',
                    'time',
                    'message_id',
                    'username',
                    'comment'
                ),
                'where' => array(
                    array('id', '=', $id)
                )
            )
        );
        
        $find_comments_response_body_json = $find_comments_response->body();
        
        $find_comments_response_body_array = json_decode($find_comments_response_body_json);
        
        $found_comments = $find_comments_response_body_array->data;
        
        $error_message = null;
        
        if (Input::method() == 'POST') {
            $edit_comment_response = Tool_Ask::request_curl(
                'api/edit_comment',
                'json',
                'post',
                array(
                    'id' => $id,
                    'comment' => Input::post('comment')
                )
            );
            
            $edit_comment_response_body_json = $edit_comment_response->body();
            
            $edit_comment_response_body_array = json_decode($edit_comment_response_body_json);
            
            if ($edit_comment_response_body_array->success == 'false') {
                $error_message = $edit_comment_response_body_array->msg;
            } else {
                Response::redirect('common/view_message/'.$found_comments[0]->message_id);
            }
        }
        
        $view = View::forge('common/edit_comment');
        
        if (isset($error_message)) {
            $view->set('error_message', $error_message, false);
        }
        
        $view->set_global('found_comment', $found_comments[0], true);
        
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
        
        $find_comments_response = Tool_Ask::request_curl(
            'api/find_comments',
            'json',
            'get',
            array(
                'select' => array(
                    'id',
                    'message_id',
                ),
                'where' => array(
                    array('id', '=', $id)
                )
            )
        );
        
        $find_comments_response_body_json = $find_comments_response->body();
        
        $find_comments_response_body_array = json_decode($find_comments_response_body_json);
        
        $found_comments = $find_comments_response_body_array->data;
        
        $response = Tool_Ask::request_curl(
            'api/delete_comment',
            'json',
            'post',
            array(
                'id' => $id,
            )
        );
        
        $response_body_json = $response->body();
        
        $response_body_array = json_decode($response_body_json);
        
        $is_succeed = $response_body_array->success;
        
        if ($is_succeed == 'false') {
            Response::redirect('common/delete_comment_fail');
        }
        
        Response::redirect('common/view_message/'.$found_comments[0]->message_id);
    }
    
    /**
     * 將頁面導向views/common/delete_comment_fail.php
     */
    public function action_delete_comment_fail()
    {
        $this->template->title = "Sorry, Can't Delete The Comment";
        $this->template->content = View::forge('common/delete_fail');
    }
}