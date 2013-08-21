<?php
class Controller_Redistest extends \Controller_Template
{
    public function action_index()
    {
        $redis = Redis::instance('default');
        
        
    }
}