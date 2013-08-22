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
	    
		$data['users'] = Model_User::find_all();
		$data['messages'] = Model_Message::find(array(
		    'select' => array('*'),
		    'order_by' => array('id' => 'desc'),
		));
		
		$this->template->title = "Admin >> Control Panel";
		$this->template->content = View::forge('admin/index', $data);
	}
	
	/**
	 * 將頁面導向views/admin/view_user_logs.php，觀看所有使用者log的頁面
	 */
	public function action_view_user_logs()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $data = null;
	    
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
	        
	        $data['logs'] = Model_UserLog::find(array(
	            'select' => array('*'),
	            'where' => $conditions,
	            'order_by' => array(
	                'id' => 'desc',
	            ),
	        ));
	    } else {
	        $data['logs'] = Model_UserLog::find(array(
	            'select' => array('*'),
	            'order_by' => array('id' => 'desc'),
	        ));
	    }
	    
	    $this->template->title = "Admin >> View User Logs";
	    $this->template->content = View::forge('admin/view_user_logs', $data);
	}
	
	/**
	 * 將頁面導向views/admin/view_message_logs.php，觀看所有訊息log的頁面
	 */
	public function action_view_message_logs()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $data = null;
	    
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
	        
	        $data['logs'] = Model_MessageLog::find(array(
	            'select' => array('*'),
	            'where' => $conditions,
	            'order_by' => array(
	                'id' => 'desc',
	            ),
	        ));
	    } else {
	        $data['logs'] = Model_MessageLog::find(array(
	            'select' => array('*'),
	            'order_by' => array('id' => 'desc'),
	        ));
	    }
	    
	    $this->template->title = "Admin >> View Message Logs";
	    $this->template->content = View::forge('admin/view_message_logs', $data);
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
	    
	    if ($user = Model_User::find_by_pk($id)) {
	        $user->delete();
	        
	        Session::set_flash('success', 'Deleted user # '.$id);
	    } else {
	        Session::set_flash('error', 'Could not delete user # '.$id);
	    }
	    
	    Response::redirect('admin');
	}
	
	/**
	 * 將頁面導向views/admin/view_message.php，內容為顯示在留言版上的單一訊息
	 */
	public function action_view_message($id = null)
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    if ( ! $found_message = Model_Message::find_by_pk($id)) {
	        Response::redirect('admin');
	    } else {
	        $this->template->set_global('found_message', $found_message, false);
	        
	        $this->template->title = "Admin >> View Message";
	        $this->template->content = View::forge('admin/view_message');
	    }
	}
	
	/**
	 * 將頁面導向views/admin/add_message.php，
	 * 若未建立新訊息時顯示建立新訊息的頁面，
	 * 若使用者建立的標題或訊息長度不足時顯示錯誤訊息
	 */
	public function action_add_message()
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
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
	                
	                Response::redirect('admin');
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
	        $this->template->title = "Admin >> Add Message (The Title And Message Should Be At Least \"1\" Character)";
	        $this->template->content = View::forge('admin/add_message');
	    } else {
	        $this->template->title = "Admin >> Add Message";
	        $this->template->content = View::forge('admin/add_message');
	    }
	}
	
	/**
	 * 將頁面導向views/admin/edit_message.php，修改一則所選的訊息
	 */
	public function action_edit_message($id = null)
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    $before_title = null;
	    $before_message = null;
	    
	    if ( ! $found_message = Model_Message::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find message # '.$id);
	        
	        Response::redirect('admin');
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
	                
	                Session::set_flash('success', 'Updated message # '. $id);
	                
	                Response::redirect('admin');
	            } else {
	                Session::set_flash('error', 'Could not update message # '. $id);
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
	        $this->template->title = "Admin >> Edit Message (The Title And Message Should Be At Least \"1\" Character)";
	        $this->template->content = View::forge('admin/edit_message');
	    } else {
	        $this->template->title = "Admin >> Edit Message";
	        $this->template->content = View::forge('admin/edit_message');
	    }
	}
	
	/**
	 * 刪除一則所選的訊息
	 */
	public function action_delete_message($id = null)
	{
	    if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
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
	    
	    Response::redirect('admin');
	}
}
