<?php
class Tool_Ask {
    public static function request_curl(
        $uri,
        $response_type,
        $method,
        $params
    ) {
        $curl = Request::forge($uri.'.'.$response_type, 'curl');
        
        $curl->set_method($method);
        $curl->set_params($params);
        $curl->set_auto_format(false);
        
        /* echo '<pre>'; print_r($curl);
        
        exit; */
        
        $curl->execute();
        
        $response = $curl->response();
        
        return $response;
        
        /* $body = $response->body();
        
        if ($response_type == 'json') {
            return $json = json_decode($body);
        } */
    }
}
