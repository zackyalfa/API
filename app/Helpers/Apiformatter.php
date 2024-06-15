<?php

//namespace : menentukan lokasi folder dari file ini
namespace app\Helpers;

//name class == nama file
class Apiformatter {
    //variable struktur yang akan ditampilkan atau di respone postman
    protected static $response = [
        "status" => Null,
        "message" => Null,
        "data" => Null
    ];

    public static function sendResponse($status = Null, $message = Null, $data = Null)
    {
        self::$response['status'] = $status;
        self::$response['message'] = $message;
        self::$response['data'] = $data;
        return response()->json(self::$response,self::$response['status']);
        //status : http status code ( 200,400,500)
        //message : desc http status code ('success', 'bad request', server error')
        //data : hasil yang diambil dari db
    }

}
