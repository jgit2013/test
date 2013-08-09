<?php
class Controller_Message extends \Controller_Template
{
    public function action_index()
    {
        /* if (is_null(Session::get('is_login')) && (Session::get('is_login') == 'False')) {
            Response::redirect('login');
        } */
        
        is_null(Session::get('is_login')) and Response::redirect('main');
        
        $data['messages'] = Model_Message::find(array(
            'select' => array('*'),
            'order_by' => array('id' => 'desc'),
        )); //find all the records in the table and order by id desc
        
        //$data['messages'] = Model_Message::find_all(); //find all the records in the table
        
        //echo '<pre>'; print_r($data); //print format array
        
        $this->template->title = "Hello, \"".Session::get('username')."\"";
        $this->template->content = View::forge('message/index', $data);
    }
    
    public function action_view($id = null)
    {
        is_null($id) and Response::redirect('message');
        
        if ( ! $data['message'] = Model_Message::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find message # '.$id);
            
            Response::redirect('message');
        }
        
        $this->template->title = "Message >> View";
        $this->template->content = View::forge('message/view', $data);
    }
    
    public function action_create()
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
                    
                    Response::redirect('message');
                } else {
                    Session::set_flash('error', 'Could not save message.');
                }
            } else {
                Session::set_flash('error', $val->error());
            }
        }
        
        $this->template->title = "Messages >> Create";
        $this->template->content = View::forge('message/create');
    }
    
    public function action_edit($id = null)
    {
        is_null($id) and Response::redirect('message');
        
        if ( ! $message = Model_Message::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find message # '.$id);
            
            Response::redirect('message');
        }
        
        $val = Model_Message::validate('edit');
        
        if ($val->run()) {
            $message->title = Input::post('title');
            $message->message = Input::post('message');
            
            if ($message->save()) {
                Session::set_flash('success', 'Updated message # ' . $id);
                
                Response::redirect('message');
            } else {
                Session::set_flash('error', 'Could not update message # ' . $id);
            }
        } else {
            if (Input::method() == 'POST') {
                $message->title = $val->validated('title');
                $message->message = $val->validated('message');
                
                Session::set_flash('error', $val->error());
            }
            
            $this->template->set_global('message', $message, false);
        }
        
        $this->template->title = "Messages >> Edit";
        $this->template->content = View::forge('message/edit');
    }
    
    public function action_delete($id = null)
    {
        is_null($id) and Response::redirect('message');
        
        if ($message = Model_Message::find_by_pk($id)) {
            $message->delete();
            
            Session::set_flash('success', 'Deleted message # '.$id);
        } else {
            Session::set_flash('error', 'Could not delete message # '.$id);
        }
        
        Response::redirect('message');
    }
}
