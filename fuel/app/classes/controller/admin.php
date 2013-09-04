<?php
/**
 * Admin Controller class
 *
 * 管理者登入後留言版操作頁面的Controller
 * 
 * @author    J
 */
class Controller_Admin extends \Controller_Template
{
    /**
     * 將頁面導向views/admin/index.php，內容為管理者的留言版頁面，
     * 除了可以建立新的使用者和訊息外，也可以觀看、修改或刪除所有人建立的訊息，
     * 而訊息排列方式會由最新的訊息排到最舊的訊息
     */
	public function action_index()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $find_users_response = Tool_Ask::request_curl(
	        'api/find_users',
	        'json',
	        'get',
	        array(
	            'select' => array(
	                'id',
	                'username',
	                'password',
	                'is_admin'
	            )
	        )
	    );
	    
	    $find_users_response_body_json = $find_users_response->body();
	    
	    $find_users_response_body_array = json_decode($find_users_response_body_json);
	    
	    $users = $find_users_response_body_array->data;
	    
	    $find_messages_response = Tool_Ask::request_curl(
	        'api/find_messages',
	        'json',
	        'get',
	        array(
	            'select' => array(
	                'id',
	                'time',
	                'username',
	                'title',
	                'message'
	            ),
	            'order_by' => array('id' => 'desc')
	        )
	    );
	    
	    $find_messages_response_body_json = $find_messages_response->body();
	    
	    $find_messages_response_body_array = json_decode($find_messages_response_body_json);
	    
	    $messages = $find_messages_response_body_array->data;
	    
	    $comments = array();
	    
	    $view = View::forge('admin/index');
	    
	    if (isset($users)) {
	        $view->set('users', $users, true);
	    }
	    
	    if (isset($messages)) {
	        foreach ($messages as $message) {
	            $results = DB::select()->from('comments')->where('message_id', $message->id)->execute();
	            
	            $comments[$message->id] = count($results);
	        }
	        
	        $view->set('messages', $messages, true);
	        $view->set('comments', $comments, true);
	    }
	    
	    $this->template->title = "Admin >> Control Panel";
	    $this->template->content = $view;
	}
	
	/**
	 * 將頁面導向views/admin/view_user_logs.php，觀看所有使用者log的頁面
	 */
	public function action_view_user_logs()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $found_user_logs = null;
	    
	    if (Input::method() == 'POST') {
	        $input = Model_UserLog::forge(array(
	            'username' => Input::post('username'),
	            'ip_address' => Input::post('ip_address'),
	            'sign_in_time' => Input::post('sign_in_time'),
	            'sign_out_time' => Input::post('sign_out_time'),
	        ));
	        
	        $conditions = null;
	        
	        if ($input->ip_address != '') {
	            $conditions[] = array('ip_address', 'like', '%'.$input->ip_address.'%');
	        }
	        
	        if ($input->username != '') {
	            $conditions[] = array('username', '=', $input->username);
	        }
	        
	        if ($input->sign_in_time != '') {
	            $conditions[] = array('sign_in_time', 'like', '%'.$input->sign_in_time.'%');
	        }
	        
	        if ($input->sign_out_time != '') {
	            $conditions[] = array('sign_out_time', 'like', '%'.$input->sign_out_time.'%');
	        }
	        
	        $find_user_logs_response = Tool_Ask::request_curl(
	            'api/find_user_logs',
	            'json',
	            'post',
	            array(
	                'select' => array(
	                    'ip_address',
	                    'username',
	                    'sign_in_time',
	                    'sign_out_time',
	                    'during'
                    ),
	                'where' => $conditions,
	                'order_by' => array('id' => 'desc')
	            )
	        );
	        
	        $find_user_logs_response_body_json = $find_user_logs_response->body();
	        
	        $find_user_logs_response_body_array = json_decode($find_user_logs_response_body_json);
	        
	        $found_user_logs = $find_user_logs_response_body_array->data;
	    } else {
	        $find_user_logs_response = Tool_Ask::request_curl(
	            'api/find_user_logs',
	            'json',
	            'post',
	            array(
	                'select' => array(
	                    'ip_address',
	                    'username',
	                    'sign_in_time',
	                    'sign_out_time',
	                    'during'
	                ),
	                'order_by' => array('id' => 'desc')
	            )
	        );
	        
	        $find_user_logs_response_body_json = $find_user_logs_response->body();
	        
	        $find_user_logs_response_body_array = json_decode($find_user_logs_response_body_json);
	        
	        $found_user_logs = $find_user_logs_response_body_array->data;
	    }
	    
	    $view = View::forge('admin/view_user_logs');
	    
	    if (isset($found_user_logs)) {
	        $view->set('found_user_logs', $found_user_logs, true);
	    }
	    
	    $this->template->title = "Admin >> View User Logs";
	    $this->template->content = $view;
	}
	
	/**
	 * 將頁面導向views/admin/view_message_logs.php，觀看所有訊息log的頁面
	 */
	public function action_view_message_logs()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $found_message_logs = null;
	    
	    if (Input::method() == 'POST') {
	        $input = Model_MessageLog::forge(array(
	            'time' => Input::post('time'),
	            'username' => Input::post('username'),
	            'action' => Input::post('action'),
	            'before_title' => Input::post('before_title'),
	            'after_title' => Input::post('after_title'),
	            'before_message' => Input::post('before_message'),
	            'after_message' => Input::post('after_message'),
	            'is_succeed' => Input::post('is_succeed'),
	        ));
	        
	        $conditions = null;
	        
	        if ($input->time != '') {
	            $conditions[] = array('time', 'like', '%'.$input->time.'%');
	        }
	        
	        if ($input->username != '') {
	            $conditions[] = array('username', '=', $input->username);
	        }
	        
	        if ($input->action != '') {
	            $conditions[] = array('action', '=', $input->action);
	        }
	        
	        if ($input->before_title != '') {
	            $conditions[] = array('before_title', 'like', '%'.$input->before_title.'%');
	        }
	        
	        if ($input->after_title != '') {
	            $conditions[] = array('after_title', 'like', '%'.$input->after_title.'%');
	        }
	        
	        if ($input->before_message != '') {
	            $conditions[] = array('before_message', 'like', '%'.$input->before_message.'%');
	        }
	        
	        if ($input->after_message != '') {
	            $conditions[] = array('after_message', 'like', '%'.$input->after_message.'%');
	        }
	        
	        if ($input->is_succeed != '') {
	            $conditions[] = array('is_succeed', '=', $input->is_succeed);
	        }
	        
	        $find_message_logs_response = Tool_Ask::request_curl(
	            'api/find_message_logs',
	            'json',
	            'post',
	            array(
	                'select' => array(
	                    'time',
	                    'username',
	                    'action',
	                    'before_title',
	                    'after_title',
	                    'before_message',
	                    'after_message',
	                    'is_succeed'
	                ),
	                'where' => $conditions,
	                'order_by' => array('id' => 'desc')
	            )
	        );
	        
	        $find_message_logs_response_body_json = $find_message_logs_response->body();
	        
	        $find_message_logs_response_body_array = json_decode($find_message_logs_response_body_json);
	        
	        $found_message_logs = $find_message_logs_response_body_array->data;
	    } else {
	        $find_message_logs_response = Tool_Ask::request_curl(
	            'api/find_message_logs',
	            'json',
	            'post',
	            array(
	                'select' => array(
	                    'time',
	                    'username',
	                    'action',
	                    'before_title',
	                    'after_title',
	                    'before_message',
	                    'after_message',
	                    'is_succeed'
	                ),
	                'order_by' => array('id' => 'desc')
	            )
	        );
	        
	        $find_message_logs_response_body_json = $find_message_logs_response->body();
	        
	        $find_message_logs_response_body_array = json_decode($find_message_logs_response_body_json);
	        
	        $found_message_logs = $find_message_logs_response_body_array->data;
	    }
	    
	    $view = View::forge('admin/view_message_logs');
	    
	    if (isset($found_message_logs)) {
	        $view->set('found_message_logs', $found_message_logs, true);
	    }
	    
	    $this->template->title = "Admin >> View Message Logs";
	    $this->template->content = $view;
	}
	
	/**
	 * 刪除所選的使用者
	 */
	public function action_delete_user($id = null)
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    $response = Tool_Ask::request_curl(
	        'api/delete_user',
	        'json',
	        'post',
	        array(
	            'id' => $id
	        )
	    );
	    
	    $response_body_json = $response->body();
	    
	    $response_body_array = json_decode($response_body_json);
	    
	    $is_succeed = $response_body_array->success;
	    
	    if ($is_succeed == 'false') {
	        Response::redirect('admin/back');
	    }
	    
	    Response::redirect('admin');
	}
	
	/**
	 * 將頁面導向views/admin/back.php
	 */
	public function action_back()
	{
	    $this->template->title = "Sorry, Can't Delete The User";
	    $this->template->content = View::forge('admin/back');
	}
}
