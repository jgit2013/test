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
    
    //common
    'view_message/(:any)' => 'common/view_message/$1',
    'add_message' => 'common/add_message',
    'edit_message/(:any)' => 'common/edit_message/$1',
    'delete_message/(:any)' => 'common/delete_message/$1',
    'add_comment/(:any)' => 'common/add_comment/$1',
    'edit_comment/(:any)' => 'common/edit_comment/$1',
    'delete_comment/(:any)' => 'common/delete_comment/$1',
    
    //admin
    'delete_user/(:any)' => 'admin/delete_user/$1',
    'view_user_logs' => 'admin/view_user_logs',
    'view_message_logs' => 'admin/view_message_logs',
);