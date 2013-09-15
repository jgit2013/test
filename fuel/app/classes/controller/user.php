<?php
/**
 * User Controller class
 *
 * 一般使用者登入後留言版操作頁面的Controller
 *
 * @author    J
 */
class Controller_User extends \Controller_Template
{
    /**
     * 將頁面導向views/user/index.php，
     * 內容為留言版的頁面，
     * 除了可以建立新的訊息外，也可選擇觀看自己或其他人建立的訊息，
     * 且修改或刪除自己留的訊息 ，而訊息排列方式會由最新的訊息排到最舊的訊息
     */
    public function action_index()
    {
        if (is_null(Session::get('is_sign_in')) || (Session::get('is_admin') == '1')) {
            Response::redirect('404');
        }
        
        $response = Tool_Ask::request_curl(
            'api/get/find_messages',
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
        
        $body_json = $response->body();
        
        $body_array = json_decode($body_json);
        
        $messages = $body_array->data;
        
        $comments = array();
        
        $view = View::forge('user/index');
        
        if (isset($messages)) {
            foreach ($messages as $message) {
                $results = DB::select()->from('comments')->where('message_id', $message->id)->execute();
                
                $comments[$message->id] = count($results);
            }
            
            $view->set('messages', $messages, true);
            $view->set('comments', $comments, true);
        }
        
        $this->template->title = "Hello, \"".Session::get('username')."\"";
        $this->template->content = $view;
    }
}
