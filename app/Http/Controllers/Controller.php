<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendResponse($result, $message, $code = 1)
    {
        $response = [
            'returnCode' => $code,
            'data'    => $result,
            'returnMessage' => $message,
        ];


        return response()->json($response, 200);
    }
}
