<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Respond with error message
     *
     * @param $message
     * @param $data
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $data = [], $status = 400)
    {
        $error = ['error' => $message];

        if (!empty($data)) {
            $error = array_merge($error, $data);
        }

        return response()->json($error, $status);
    }
}
