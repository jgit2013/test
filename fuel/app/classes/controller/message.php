<?php
class Controller_Message extends \Controller_Template {
	public function action_index() {
		$data['messages'] = Model_Message::find('all');
	
		$this->template->title = "Messages";
		$this->template->content = View::forge('message/index', $data);
	}
	
	public function action_create($id = null) {
		if (Input::method() == 'POST') {
			$val = Model_Message::validate('create');
			
			if ($val->run()) {
				$message = Model_Message::forge(array(
						'id' => Input::post('id'),
						'title' => Input::post('title'),
						'message' => Input::post('message'),
				));
				
				if ($message and $message->save()) {
					Session::set_flash('success', 'Added message #'.$message->id.'.');
					
					Response::redirect('message');
				}
				else {
					Session::set_flash('error', 'Could not save message.');
				}
			}
			else {
				Session::set_flash('error', $val->error());
			}
		}
		
		$this->template->title = "Messages >> Create";
		$this->template->content = View::forge('message/create');
	}
	
	public function action_edit($id = null) {
		is_null($id) and Response::redirect('message');
		
		if ( ! $message = Model_Message::find($id)) {
			Session::set_flash('error', 'Could not find message #'.$id);
			Response::redirect('message');
		}
		
		$val = Model_Message::validate('edit');
		
		if ($val->run()) {
			$message->id = Input::post('id');
			$message->title = Input::post('title');
			$message->message = Input::post('message');
			
			if ($message->save()) {
				Session::set_flash('success', 'Updated message #' . $id);
				
				Response::redirect('message');
			}
			else {
				Session::set_flash('error', 'Could not update message #' . $id);
			}
		}
		else {
			if (Input::method() == 'POST') {
				$message->id = $val->validated('id');
				$message->title = $val->validated('title');
				$message->message = $val->validated('message');
				
				Session::set_flash('error', $val->error());
			}
			
			$this->template->set_global('message', $message, false);
		}
		
		$this->template->title = "Messages >> Edit";
		$this->template->content = View::forge('message/edit');
	}
	
	/* public function action_index() {
		$data['messages'] = Model_Message::find('all');
		
		$this->template->title = "Messages";
		$this->template->content = View::forge('message/index', $data);

	}

	public function action_view($id = null)
	{
		is_null($id) and Response::redirect('message');

		if ( ! $data['message'] = Model_Message::find($id))
		{
			Session::set_flash('error', 'Could not find message #'.$id);
			Response::redirect('message');
		}

		$this->template->title = "Message";
		$this->template->content = View::forge('message/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Message::validate('create');
			
			if ($val->run())
			{
				$message = Model_Message::forge(array(
					'id' => Input::post('id'),
					'title' => Input::post('title'),
					'message' => Input::post('message'),
				));

				if ($message and $message->save())
				{
					Session::set_flash('success', 'Added message #'.$message->id.'.');

					Response::redirect('message');
				}

				else
				{
					Session::set_flash('error', 'Could not save message.');
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Messages";
		$this->template->content = View::forge('message/create');

	}

	public function action_edit($id = null)
	{
		is_null($id) and Response::redirect('message');

		if ( ! $message = Model_Message::find($id))
		{
			Session::set_flash('error', 'Could not find message #'.$id);
			Response::redirect('message');
		}

		$val = Model_Message::validate('edit');

		if ($val->run())
		{
			$message->id = Input::post('id');
			$message->title = Input::post('title');
			$message->message = Input::post('message');

			if ($message->save())
			{
				Session::set_flash('success', 'Updated message #' . $id);

				Response::redirect('message');
			}

			else
			{
				Session::set_flash('error', 'Could not update message #' . $id);
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$message->id = $val->validated('id');
				$message->title = $val->validated('title');
				$message->message = $val->validated('message');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('message', $message, false);
		}

		$this->template->title = "Messages";
		$this->template->content = View::forge('message/edit');

	}

	public function action_delete($id = null)
	{
		is_null($id) and Response::redirect('message');

		if ($message = Model_Message::find($id))
		{
			$message->delete();

			Session::set_flash('success', 'Deleted message #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete message #'.$id);
		}

		Response::redirect('message');

	} */
}
