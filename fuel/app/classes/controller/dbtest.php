<?php
class Controller_DBTest extends \Controller {
    public function action_index() {
        $query_select = 'SELECT id, username, password FROM users ORDER BY id DESC';
        $query_insert_into = "INSERT INTO users (username, password) VALUES ('sqltest', 'test')";
        $query_update = "UPDATE users SET username='helloSQL', password='test123' WHERE id='4'";
        $query_delete = "DELETE FROM users WHERE id='3'";
        
        /* echo $query_update.'<br>';
        
        exit; */
        
        /* $result_create = \DB::query($query_insert_into)->execute();  //create
        
        echo '<pre>'; print_r($result_create); */
        
        /* $result_edit = \DB::query($query_update)->execute();  //edit
        
        echo '<pre>'; print_r($result_edit); */
        
        /* $result_remove = \DB::query($query_delete)->execute();  //remove
        
        echo '<pre>'; print_r($result_remove); */
        
        $result_find = \DB::query($query_select)->execute()->as_array();  //find
        
        /* $data = array();
        
        foreach ($result_find as $record) {
            $data[] = $record;
        } */
        
        $body = array(
            'data' => $result_find
        );
        
        echo '<pre>'; print_r($body);
        
        /* foreach ($result_find as $record) {
            echo $record['id'].'<br>';
            echo $record['username'].'<br>';
            echo $record['password'].'<br>';
        } */
        
        exit;
    }
    
    public function action_model() {
        $username = 'OoO';
        $password = '1234';
        
        $found_user = Model_User::check_user(
            $username,
            $password,
            'IN TABLE'
        );
        
        echo '<pre>'; print_r($found_user);
        
        exit;
    }
}