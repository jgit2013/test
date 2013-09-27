<?php
/**
 * Api class
 *
 * Controller中所有相關API的操作
 *
 * @author  J
 */
class Api {
    public static function request_curl(
        $uri = null,
        //$response_type = 'json',
        $method = null,
        $params = array()
    ) {
        $curl = Request::forge($uri/* .'.'.$response_type */, 'curl');
        
        $curl->set_method($method);
        $curl->set_params($params);
        //$curl->set_auto_format(false);
        
        $curl->execute();
        
        $response = $curl->response();
        
        return $response;
        
        /* $body = $response->body();
        
        if ($response_type == 'json') {
            return $json = json_decode($body);
        } */
    }
}
