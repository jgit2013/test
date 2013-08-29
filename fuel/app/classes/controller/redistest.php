<?php

class Controller_Redistest extends \Controller_Template
{
    public function action_index()
    {
        $shd_array = Dom::get_shd();
        
        //echo '<pre>'; print_r($shd_array);
        
        $redis = Redis::forge();
        
        $ser_shd_array = serialize($shd_array);
        
        //echo '<pre>'; print_r($ser_shd_array);
        
        //$unser_shd_array = unserialize($ser_shd_array);
        
        //echo '<pre>'; print_r($unser_shd_array);
        
        $redis = Redis::forge();
        
        /* echo '<pre>'; var_dump($redis);
        
        exit; */
        
        $redis->hset('mytables', 'table1', $ser_shd_array);
        
        $view = View::forge('redistest/index');
        
        /* if (isset($shd_array)) {
            $view->set('shd_array', $shd_array, true);
        } */
        
        if (isset($redis)) {
            $view->set('redis', $redis, true);
        }
        
        $this->template->title = "Redis Test";
        $this->template->content = $view;
    }
}
