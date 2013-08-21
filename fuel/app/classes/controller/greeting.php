<?php
class Controller_Greeting extends \Controller_Template
{
    public function action_index()
    {
        $this->template->title = 'Greeting &raquo; Index';
        $this->template->content = View::forge('greeting/index');
    }
    
    public function action_publish()
    {
        $redis = Redis::instance('default');
        $redis->publish('greeting', 'おはよう諸君！！');
        
        $this->template->title = 'Greeting &raquo; Publish';
        $this->template->content = View::forge('greeting/publish');
    }
}
