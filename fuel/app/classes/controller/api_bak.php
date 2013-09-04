<?php
class Controller_Api extends \Controller_Rest
{
    protected $format = 'json';
    
    /* public function get_index()
    {
        if (Input::get('id')) {
            $user = Model_User::find_by_pk(Input::get('id'));
            
            if ($user) {
                $respuesta = array(
                    "success" => "true",
                    "data" => $user
                );
                
                $this->response($respuesta, 200);
            } else {
                $respuesta = array(
                    "error" => "true",
                    "msg" => 'id is incorrect'
                );
                
                $this->response($respuesta, 404);
            }
        } else {
            $users = Model_User::find_all();
            
            if ($users) {
                $respuesta = array(
                    "success" => "true",
                    "data" => $users
                );
                
                $this->response($respuesta, 200);
            } else {
                $respuesta = array(
                    "error" => "true",
                    "msg" => 'No Users'
                );
                
                $this->response($respuesta, 404);
            }
        }
    } */
    
    public function get_index()
    {
        if (Input::post('id')) {
            echo 'OK';
            
            exit;
            
            Response::redirect('api/find');
        } else {
            $view = View::forge('api/index');
            
            echo $view;
            
            return;
        }
    }
    
    public function post_index() {
        if (Input::post('id')) {
            $user = Model_User::find_by_pk(Input::post('id'));
        
            if ($user) {
                $respuesta = array(
                    "success" => "true",
                    "data" => $user
                );
        
                $this->response($respuesta, 200);
                
                /* $view = View::forge('api/show');
                
                $view->set('founds', $respuesta, false);
                
                $response = Response::forge($view);
                
                $this->response = $response;
                
                return $this->response; */
            } else {
                Response::redirect('api/index');
                
                /* $respuesta = array(
                    "error" => "true",
                    "msg" => 'id is incorrect'
                );
        
                $this->response($respuesta, 404); */
            }
        } else {
            
        }
    }
    
    public function get_show() {
        
    }
    
    /* function delete_index()
    {
        if (Input::get('id')) {
            $user = Model_User::find_by_pk(Input::get('id'));
            
            if ($user) {
                $user->delete();
                
                $respuesta = array(
                    "success" => "true",
                    "data" => array(
                        'msg' => 'User Delete'
                    )
                );
                
                $this->response($respuesta, 200);
            } else {
                $respuesta = array(
                    "error" => "true",
                    "msg" => 'id is incorrect'
                );
                
                $this->response($respuesta, 404);
            }
        } else {
            $respuesta = array(
                "error" => "true",
                "msg" => 'Not Found'
            );
            
            $this->response($respuesta, 404);
        }
    } */
}
