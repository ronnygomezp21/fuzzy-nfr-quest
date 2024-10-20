<?php

class Messages{
    public static function getMessages($code){
        $messages = [
            '200' => 'Success',
            '201' => 'Created',
            '204' => 'No Content',
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '409' => 'Conflict',
            '500' => 'Internal Server Error',
            '503' => 'Service Unavailable',
        ];
        return $messages[$code];
    }
}