<?php
class Controller_Admin extends \Controller_Template
{
	public function action_index()
	{
	    if (is_null(Session::get('is_login')) || (Session::get('is_admin') == '0')) {
	        Response::redirect('main');
	    }
	    
		$data['users'] = Model_User::find_all();
		$data['messages'] = Model_Message::find(array(
		    'select' => array('*'),
		    'order_by' => array('id' => 'desc'),
		));
		
		$this->template->title = "Hello, \"".Session::get('username')."\" Here Are All The Users And Messages";
		$this->template->content = View::forge('admin/index', $data);
	}
	
	public function action_create_user()
	{
		if (Input::method() == 'POST') {
			$val = Model_User::validate('create');
			
			if ($val->run()) {
				$user = Model_User::forge(array(
					'username' => Input::post('username'),
					'password' => Input::post('password'),
				));
				
				if ($user && $user->save()) {
					Session::set_flash('success', 'Added user # '.$user->id.'.');
					
					Response::redirect('admin');
				} else {
					Session::set_flash('error', 'Could not save user.');
				}
			} else {
				Session::set_flash('error', $val->error());
			}
		}
		
		$this->template->title = "Admin >> Create User";
		$this->template->content = View::forge('admin/create_user');
	}
	
	public function action_edit_user($id = null)
	{
	    is_null($id) and Response::redirect('admin');
	    
	    if ( ! $user = Model_User::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find user # '.$id);
	        	
	        Response::redirect('admin');
	    }
	    
	    $user->is_admin = Input::post('is_admin');
	    
	    if ($user->save()) {
	        Session::set_flash('success', 'Updated user # '. $id);
	        
	        Response::redirect('admin');
	    } else {
	        Session::set_flash('error', 'Could not update user # '. $id);
	    }
	    
	    
	    
	    
	    /* $val = Model_User::validate('edit');
	    
	    if ($val->run()) {
	        $user->username = Input::post('username');
	        $user->password = Input::post('password');
	        
	        if ($user->save()) {
	            Session::set_flash('success', 'Updated user # '. $id);
	
	            Response::redirect('admin');
	        } else {
	            Session::set_flash('error', 'Could not update user # '. $id);
	        }
	    } else {
	        if (Input::method() == 'POST') {
	            $user->username = $val->validated('username');
	            $user->password = $val->validated('password');
	            
	            Session::set_flash('error', $val->error());
	        }
	        
	        $this->template->set_global('user', $user, false);
	    } */
	    
	    $this->template->title = "Admin >> Edit User";
	    $this->template->content = View::forge('admin/edit_user');
	}
	
	public function action_delete_user($id = null)
	{
	    is_null($id) and Response::redirect('admin');
	    
	    if ($user = Model_User::find_by_pk($id)) {
	        $user->delete();
	        
	        Session::set_flash('success', 'Deleted user # '.$id);
	    } else {
	        Session::set_flash('error', 'Could not delete user # '.$id);
	    }
	    
	    Response::redirect('admin');
	}
	
	public function action_view_message($id = null)
	{
	    is_null($id) and Response::redirect('admin');
	    
	    if ( ! $data['message'] = Model_Message::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find user # '.$id);
	        
	        Response::redirect('admin');
	    }
	    
	    $this->template->title = "Admin >> View Message";
	    $this->template->content = View::forge('admin/view_message', $data);
	}
	
	public function action_create_message()
	{
	    if (Input::method() == 'POST') {
	        $val = Model_Message::validate('create');
	        
	        if ($val->run()) {
	            $message = Model_Message::forge(array(
	                'username' => Session::get('username'),
	                'title' => Input::post('title'),
	                'message' => Input::post('message'),
	            ));
	            
	            if ($message && $message->save()) {
	                Session::set_flash('success', 'Added message # '.$message->id.'.');
	                
	                Response::redirect('admin');
	            } else {
	                Session::set_flash('error', 'Could not save message.');
	            }
	        } else {
	            Session::set_flash('error', $val->error());
	        }
	    }
	    
	    $this->template->title = "Admin >> Create Message";
	    $this->template->content = View::forge('admin/create_message');
	}
	
	public function action_edit_message($id = null)
	{
	    is_null($id) and Response::redirect('admin');
	    
	    if ( ! $message = Model_Message::find_by_pk($id)) {
	        Session::set_flash('error', 'Could not find message # '.$id);
	        
	        Response::redirect('admin');
	    }
	    
	    $val = Model_Message::validate('edit');
	    
	    if ($val->run()) {
	        $message->title = Input::post('title');
            $message->message = Input::post('message');
	        
	        if ($message->save()) {
	            Session::set_flash('success', 'Updated message # '. $id);
	            
	            Response::redirect('admin');
	        } else {
	            Session::set_flash('error', 'Could not update message # '. $id);
	        }
	    } else {
	        if (Input::method() == 'POST') {
	            $message->title = $val->validated('title');
                $message->message = $val->validated('message');
                
	            Session::set_flash('error', $val->error());
	        }
	        
	        $this->template->set_global('message', $message, false);
	    }
	    
	    $this->template->title = "Admin >> Edit Message";
	    $this->template->content = View::forge('admin/edit_message');
	}
	
	public function action_delete_message($id = null)
	{
	    is_null($id) and Response::redirect('admin');
	    
	    if ($message = Model_Message::find_by_pk($id)) {
	        $message->delete();
	        
	        Session::set_flash('success', 'Deleted message # '.$id);
	    } else {
	        Session::set_flash('error', 'Could not delete message # '.$id);
	    }
	    
	    Response::redirect('admin');
	}
}
