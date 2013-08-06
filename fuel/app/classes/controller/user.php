<?php
class Controller_User extends \Controller_Template
{
	public function action_index()
	{
		$data['users'] = Model_User::find_all();
		
		$this->template->title = "Users >> Index";
		$this->template->content = View::forge('user/index', $data);
	}
	
	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('user');
		
		if ( ! $data['user'] = Model_User::find_by_pk($id)) {
			Session::set_flash('error', 'Could not find user #'.$id);
			
			Response::redirect('user');
		}
		
		$this->template->title = "User >> View";
		$this->template->content = View::forge('user/view', $data);
	}
	
	public function action_create()
	{
		if (Input::method() == 'POST') {
			$val = Model_User::validate('create');
			
			if ($val->run()) {
				$user = Model_User::forge(array(
					'username' => Input::post('username'),
					'password' => Input::post('password'),
				));
				
				if ($user && $user->save()) {
					Session::set_flash('success', 'Added user #'.$user->id.'.');
					
					Response::redirect('user');
				} else {
					Session::set_flash('error', 'Could not save user.');
				}
			} else {
				Session::set_flash('error', $val->error());
			}
		}
		
		$this->template->title = "Users >> Create";
		$this->template->content = View::forge('user/create');
	}
	
	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('user');
		
		if ( ! $user = Model_User::find_by_pk($id)) {
			Session::set_flash('error', 'Could not find user #'.$id);
			
			Response::redirect('user');
		}
		
		$val = Model_User::validate('edit');
		
		if ($val->run()) {
			//$user->username = Input::post('username');
			$user->password = Input::post('password');
			
			if ($user->save()) {
				Session::set_flash('success', 'Updated user #' . $id . '\'s password');
				
				Response::redirect('user');
			} else {
				Session::set_flash('error', 'Could not update user #' . $id . '\'s password');
			}
		} else {
			if (Input::method() == 'POST') {
				//$user->username = $val->validated('username');
				$user->password = $val->validated('password');
				
				Session::set_flash('error', $val->error());
			}
			
			$this->template->set_global('user', $user, false);
		}
		
		$this->template->title = "Users >> Edit Password";
		$this->template->content = View::forge('user/edit');
	}
	
	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('user');
		
		if ($user = Model_User::find_by_pk($id)) {
			$user->delete();
			
			Session::set_flash('success', 'Deleted user #'.$id);
		} else {
			Session::set_flash('error', 'Could not delete user #'.$id);
		}
		
		Response::redirect('user');
	}
}
