<?php
return array(
	//'_root_'  => 'welcome/index',  // The default route
	'_root_'  => 'main',
	//'_404_'   => 'welcome/404',    // The main 404 route
    '_404_'   => '404',
	
    //main
    'sign_in' => 'main/sign_in',
    'sign_out' => 'main/sign_out',
    'sign_up' => 'main/sign_up',
    'go' => 'main/go',
    
    //message
    //'add' => 'message/add',
    
    //admin
    'add_message' => 'admin/add_message',
    'view_user_logs' => 'admin/view_user_logs',
    'view_message_logs' => 'admin/view_message_logs',
);