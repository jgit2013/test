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
     *
     * 將頁面導向views/admin/index.php，內容為管理者的留言版頁面，
     * 除了可以建立新的使用者和訊息外，也可以觀看、修改或刪除所有人建立的訊息，
     * 而訊息排列方式會由最新的訊息排到最舊的訊息
     *
     */
	public function action_index()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
		$data['users'] = Model_User::find_all();
		$data['messages'] = Model_Message::find(array(
		    'select' => array('*'),
		    'order_by' => array('id' => 'desc'),
		));
		
		$this->template->title = "Hello, \"".Session::get('username')."\" Here Are All The Users And Messages";
		$this->template->content = View::forge('admin/index', $data);
	}
	
	/**
	 *
	 * 將頁面導向views/admin/view_logs.php，觀看所有log的頁面，
	 *
	 */
	public function action_view_logs()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $data = null;
	    
	    if (Input::method() == 'POST') {
	        $input = Model_Log::forge(array(
	            'time' => Input::post('time'),
	            'username' => Input::post('username'),
	            'action' => Input::post('action'),
	            'is_succeed' => Input::post('is_succeed'),
	        ));
	        
	        $result = \DB::query("SELECT * FROM logs WHERE username='".Input::post('username')."' OR action='".Input::post('action')."' ORDER BY id DESC")->execute();
	        
	        $data['logs'] = Model_Log::forge($result->as_array());
	        
	        echo '<pre>'; print_r($data['logs']);
	        
	        exit; //debug test
	        
// 	        $data['logs'] = Model_Log::find(array(
// 	            'select' => array('*'),
// 	            'where' => array(
// 	                array('username', '=', $input->username),
// 	                array('action', '=', $input->action),
// 	                /* 'or' => array(
// 	                    array('action', '=', $input->action),
// 	                ), */
// 	            ),
// 	            'order_by' => array(
// 	                'id' => 'desc',
// 	            ),
// 	        ));
	        
// 	        echo '<pre>'; print_r($data['logs']);
	        
// 	        exit;
	    } else {
	        $data['logs'] = Model_Log::find(array(
	            'select' => array('*'),
	            'order_by' => array('id' => 'desc'),
	        ));
	    }
	    
	    $this->template->title = "Admin >> View Logs";
	    $this->template->content = View::forge('admin/view_logs', $data);
	}
	
	/**
	 *
	 * 將頁面導向views/admin/view_logs.php，搜尋log的頁面，
	 *
	 */
	/* public function action_find_logs()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    if (Input::method() == 'POST') {
	        
	    }
	    
	    $this->template->title = "Admin >> View Logs";
	    $this->template->content = View::forge('admin/view_logs');
	} */
	
	/**
	 *
	 * 將頁面導向views/admin/create_user.php，若未建立新使用者時顯示建立新使用者的頁面，
	 * 若使用者名稱或密碼長度不足時顯示錯誤訊息
	 *
	 */
	public function action_create_user()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $is_username_in_use = false;
	    $is_username_or_password_too_short = false;
	    
		if (Input::method() == 'POST') {
			$val = Model_User::validate('create');
			
			if ($val->run()) {
				$input = Model_User::forge(array(
					'username' => Input::post('username'),
					'password' => Input::post('password'),
				));
				
				$users = Model_User::find(array(
				    'select' => array('username'),
				));
				
				foreach ($users as $user) {
				    if ($input->username == $user->username) {
				        $is_username_in_use = true;
				
				        break;
				    }
				}
				
				if ( ! $is_username_in_use) {
				    if ($input and $input->save()) {
				        Session::set_flash('success', 'Added user # '.$input->id.'.');
				
				        Response::redirect('admin');
				    } else {
				        Session::set_flash('error', 'Could not save user.');
				    }
				}
			} else {
			    $is_username_or_password_too_short = true;
			    
				Session::set_flash('error', $val->error());
			}
		}
		
		if ($is_username_in_use) {
		    $this->template->title = "The Username Is Already In Use";
		    $this->template->content = View::forge('admin/create_user');
		} else {
		    if ($is_username_or_password_too_short) {
		        $this->template->title = "Admin >> Create User (Your Username Should Be At Least \"1\" Character,
		                                                  And Your Password Should Be At Least \"4\" Characters)";
		    
		        $this->template->content = View::forge('admin/create_user');
		    } else {
		        $this->template->title = "Admin >> Create User";
		        $this->template->content = View::forge('admin/create_user');
		    }
		}
	}
	
	/**
	 *
	 * 刪除所選的使用者
	 *
	 */
	public function action_delete_user($id = null)
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
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
	 *
	 * 將頁面導向views/admin/view_message.php，內容為顯示在留言版上的單一訊息
	 *
	 */
	public function action_view_message($id = null)
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    if ( ! $data['message'] = Model_Message::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find user # '.$id);
	        
	        Response::redirect('admin');
	    }
	    
	    $this->template->title = "Admin >> View Message";
	    $this->template->content = View::forge('admin/view_message', $data);
	}
	
	/**
	 *
	 * 將頁面導向views/admin/create_message.php，若未建立新訊息時顯示建立新訊息的頁面，
	 * 若使用者建立的標題或訊息長度不足時顯示錯誤訊息
	 *
	 */
	public function action_create_message()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    $is_title_or_message_too_short = false;
	    
	    if (Input::method() == 'POST') {
	        $val = Model_Message::validate('create_message');
	        
	        if ($val->run()) {
	            $message = Model_Message::forge(array(
	                'username' => Session::get('username'),
	                'title' => Input::post('title'),
	                'message' => Input::post('message'),
	            ));
	            
	            if ($message && $message->save()) {
	                $log_create = Model_Log::forge(array(
	                    'username' => Session::get('username'),
	                    'action' => 'C',
	                    'before_title' => '',
	                    'after_title' => Input::post('title'),
	                    'before_message' => '',
	                    'after_message' => Input::post('message'),
	                    'is_succeed' => '1',
	                ));
	                
	                if ($log_create && $log_create->save()) {
	                    Session::set_flash('success', 'Added log # '.$log_create->id.'.');
	                } else {
	                    Session::set_flash('error', 'Could not save log.');
	                }
	                
	                Session::set_flash('success', 'Added message # '.$message->id.'.');
	                
	                Response::redirect('admin');
	            } else {
	                Session::set_flash('error', 'Could not save message.');
	            }
	        } else {
	            $log_create = Model_Log::forge(array(
	                'username' => Session::get('username'),
	                'action' => 'C',
	                'before_title' => '',
	                'after_title' => Input::post('title'),
	                'before_message' => '',
	                'after_message' => Input::post('message'),
	                'is_succeed' => '0',
	            ));
	            
	            if ($log_create && $log_create->save()) {
	                Session::set_flash('success', 'Added log # '.$log_create->id.'.');
	            } else {
	                Session::set_flash('error', 'Could not save log.');
	            }
	            
	            $is_title_or_message_too_short = true;
	            
	            Session::set_flash('error', $val->error());
	        }
	    }
	    
	    if ($is_title_or_message_too_short) {
	        $this->template->title = "Admin >> Create Message (Your Title And Message Should Be At Least \"1\" Character)";
	        $this->template->content = View::forge('admin/create_message');
	    } else {
	        $this->template->title = "Admin >> Create Message";
	        $this->template->content = View::forge('admin/create_message');
	    }
	}
	
	/**
	 *
	 * 將頁面導向views/admin/edit_message.php，修改一則所選的訊息
	 *
	 */
	public function action_edit_message($id = null)
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    $before_title = null;
	    $before_message = null;
	    
	    if ( ! $message = Model_Message::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find message # '.$id);
	        
	        Response::redirect('admin');
	    } else {
	        $before_title = $message->title;
	        $before_message = $message->message;
	    }
	    
	    $is_title_or_message_too_short = false;
	    
	    $val = Model_Message::validate('edit_message');
	    
	    if ($val->run()) {
	        $message->title = Input::post('title');
            $message->message = Input::post('message');
	        
	        if ($message->save()) {
	            $log_update = Model_Log::forge(array(
	                'username' => Session::get('username'),
	                'action' => 'U',
	                'before_title' => $before_title,
	                'after_title' => Input::post('title'),
	                'before_message' => $before_message,
	                'after_message' => Input::post('message'),
	                'is_succeed' => '1',
	            ));
	            
	            if ($log_update && $log_update->save()) {
	                Session::set_flash('success', 'Added log # '.$log_update->id.'.');
	            } else {
	                Session::set_flash('error', 'Could not save log.');
	            }
	            
	            Session::set_flash('success', 'Updated message # '. $id);
	            
	            Response::redirect('admin');
	        } else {
	            Session::set_flash('error', 'Could not update message # '. $id);
	        }
	    } else {
	        $log_update = Model_Log::forge(array(
	            'username' => Session::get('username'),
	            'action' => 'U',
	            'before_title' => $before_title,
	            'after_title' => Input::post('title'),
	            'before_message' => $before_message,
	            'after_message' => Input::post('message'),
	            'is_succeed' => '0',
	        ));
	        
	        if ($log_update && $log_update->save()) {
	            Session::set_flash('success', 'Added log # '.$log_update->id.'.');
	        } else {
	            Session::set_flash('error', 'Could not save log.');
	        }
	        
	        if (Input::method() == 'POST') {
	            $message->title = $val->validated('title');
                $message->message = $val->validated('message');
                
	            Session::set_flash('error', $val->error());
	        }
	        
	        $is_title_or_message_too_short = true;
	        
	        $this->template->set_global('message', $message, false);
	    }
	    
	    if ($is_title_or_message_too_short) {
	        $this->template->title = "Admin >> Edit Message (The Title And Message Should Be At Least \"1\" Character)";
	        $this->template->content = View::forge('admin/edit_message');
	    } else {
	        $this->template->title = "Admin >> Edit Message";
	        $this->template->content = View::forge('admin/edit_message');
	    }
	}
	
	/**
	 *
	 * 刪除一則所選的訊息
	 *
	 */
	public function action_delete_message($id = null)
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('404');
	    }
	    
	    is_null($id) and Response::redirect('admin');
	    
	    if ($message = Model_Message::find_by_pk($id)) {
	        $log_delete = Model_Log::forge(array(
	            'username' => Session::get('username'),
	            'action' => 'D',
	            'before_title' => $message->title,
	            'after_title' => '',
	            'before_message' => $message->message,
	            'after_message' => '',
	            'is_succeed' => '1',
	        ));
	        
	        if ($log_delete && $log_delete->save()) {
	            Session::set_flash('success', 'Added log # '.$log_delete->id.'.');
	        } else {
	            $log_delete = Model_Log::forge(array(
	                'username' => Session::get('username'),
	                'action' => 'D',
	                'before_title' => $message->title,
	                'after_title' => '',
	                'before_message' => $message->message,
	                'after_message' => '',
	                'is_succeed' => '0',
	            ));
	        
	            $log_delete->save();
	        
	            Session::set_flash('error', 'Could not save log.');
	        }
	        
	        $message->delete();
	        
	        Session::set_flash('success', 'Deleted message # '.$id);
	    } else {
	        Session::set_flash('error', 'Could not delete message # '.$id);
	    }
	    
	    Response::redirect('admin');
	}
}
