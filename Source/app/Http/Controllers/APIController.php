<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class APIController extends Controller
{
    protected $raw;

    public function getParameters(Request $request)
    {
        //print_r($request);exit();
        $rawContent = $request->getContent();
        $this->raw = json_decode($rawContent,true);
        if($this->raw == null) $this->raw = array();



    }

    public function response($json,$code){
        $json = mb_convert_encoding($json, "UTF-8", "auto");
        $res = response()->json($json, $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        return $res;
    }
}
