<?php
class Library_Test {
    public static function _init()
    {
    }

    public function __toString()
    {
        return 'Library_Test';
    }
}

class Controller_Mem extends \Controller_Template
{
    public function action_index()
    {
        // String
        Cache::set('string', 'cache test');
        $data['string'] = Cache::get('string');
    
        // Array
        $a = array(1, 2, array('key' => 'value'));
        Cache::set('array', $a);
        $data['array'] = Cache::get('array');
    
        // Object
        $o = new Library_Test();
        Cache::set('object', $o);
        $data['object'] = Cache::get('object');
    
        return Response::forge(View::forge('cache/index', $data));
    }
}