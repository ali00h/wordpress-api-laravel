<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use View;
use Illuminate\Support\Facades\Artisan;



use Response;

class TestController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index(Request $request,$token)
    {
        $exitCode = Artisan::call('optimize');
        $json = array(
            "error_code"=>"195",
            "error_message"=>$exitCode,
        );
        $res = response()->json($json, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        return $res;

    }
}

