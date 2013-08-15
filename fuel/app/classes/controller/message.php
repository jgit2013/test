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
     * 將頁面導向views/message/index.php，內容為留言版的頁面，
     * 除了可以建立新的訊息外，也可選擇觀看自己或其他人建立的訊息，
     * 且修改或刪除自己留的訊息 ，而訊息排列方式會由最新的訊息排到最舊的訊息
     */
    public function action_index()
    {
        is_null(Session::get('is_login')) and Response::redirect('404');
        
        $data['messages'] = Model_Message::find(array(
            'select' => array('*'),
            'order_by' => array('id' => 'desc'),
        )); //find all the records in the table and order by id desc
        
        //$data['messages'] = Model_Message::find_all(); //find all the records in the table
        
        //echo '<pre>'; print_r($data); //print format array
        
        $this->template->title = "Hello, \"".Session::get('username')."\"";
        $this->template->content = View::forge('message/index', $data);
    }
    
    /**
     * 將頁面導向views/message/view.php，內容為顯示在留言版上的單一訊息
     */
    public function action_view($id = null)
    {
        is_null(Session::get('is_login')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        if ( ! $data['message'] = Model_Message::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find message # '.$id);
            
            Response::redirect('message');
        }
        
        $this->template->title = "Message >> View";
        $this->template->content = View::forge('message/view', $data);
    }
    
    /**
     * 將頁面導向views/main/create.php，若未建立新訊息時顯示建立新訊息的頁面，
     * 若使用者建立的標題或訊息長度不足時顯示錯誤訊息
     */
    public function action_create()
    {
        is_null(Session::get('is_login')) and Response::redirect('404');
        
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
            $this->template->title = "Messages >> Create (Your Title And Message Should Be At Least \"1\" Character)";
            $this->template->content = View::forge('message/create');
        } else {
            $this->template->title = "Messages >> Create";
            $this->template->content = View::forge('message/create');
        }
    }
    
    /**
     * 將頁面導向views/main/edit.php，修改一則該使用者自己建立的訊息
     */
    public function action_edit($id = null)
    {
        is_null(Session::get('is_login')) and Response::redirect('404');
        
        is_null($id) and Response::redirect('message');
        
        $before_title = null;
        $before_message = null;
        
        if ( ! $message = Model_Message::find_by_pk($id)) {
            Session::set_flash('error', 'Could not find message # '.$id);
            
            Response::redirect('message');
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
                $message->title = $val->validated('title');
                $message->message = $val->validated('message');
                
                Session::set_flash('error', $val->error());
            }
            
            $is_title_or_message_too_short = true;
            
            $this->template->set_global('message', $message, false);
        }
        
        if ($is_title_or_message_too_short) {
            $this->template->title = "Messages >> Edit (Your Title And Message Should Be At Least \"1\" Character)";
            $this->template->content = View::forge('message/edit');
        } else {
            $this->template->title = "Messages >> Edit";
            $this->template->content = View::forge('message/edit');
        }
    }
    
    /**
     * 刪除一則該使用者自己建立的訊息
     */
    public function action_delete($id = null)
    {
        is_null(Session::get('is_login')) and Response::redirect('404');
        
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
}
