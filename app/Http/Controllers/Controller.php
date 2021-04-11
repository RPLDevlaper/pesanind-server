<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function onSuccess($dataType, $data, $requestType = null)
    {
        if(empty($data) && is_null($data)) {
            return response()->json([
                'status' => "$dataType Not Found",
                'data' => $data
            ]);
        // } else if($data->isEmpty()) {
        //     if(count($data) == 0) {
        //         return response()->json([
        //             'status' => "$dataType Not Found",
        //             'data' => $data
        //         ]);
        //     }
        } else {
            return response()->json([
                'status' => "$dataType $requestType",
                'data' => $data
            ]);
        }
    }

    public function onError(\Exception $e)
    {
        if($e instanceof ClientException) {
            $nException = json_decode($e->getResponse()->getBody()->getContents(), true);
            if($nException) {
                $e = new \Exception($nException['reason'], $nException['code']);
            }
        }
        $exceptions = [
            'status' => 'Failed Error',
            'code' => $e->getCode(),
            'error' => $e->getMessage(),
        ];
        return response()->json($exceptions);
    }
}
