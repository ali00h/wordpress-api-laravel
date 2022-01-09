<?php
namespace App\Models;

use Illuminate\Support\Str;

class response_error
{
    private $errors = array(
        10 => array("status"=>403,"message_fa"=>"ورودی معتبر نمی باشد." , "message_en"=>"Please enter valid data."),

        38 => array("status"=>406,"message_fa"=>"درخواست شما از آدرس {domain} ارسال شده است. دامنه آدرس بازگشت callback با آدرس ثبت شده در وب سرویس همخوانی ندارد.","message_en"=>"domain not valid!"),



    );

    public $error_code = "";
    public $error_field = "";
    public $language = "fa";
    public $params = array();


    public function __construct($error_obj = null,$params = array(),$language = "fa")
    {
        if($error_obj != null) {
            foreach ($error_obj->messages() as $key => $value) {
                $this->error_code = $value[0];
                $this->error_field = $key;
            }
        }
        $this->language = $language;
        $this->params = $params;

    }

    public function get_response(){
        if(!isset($this->error_code) || $this->error_code == "") $this->error_code = 55;
        $error_obj = $this->errors[(int)$this->error_code];
        $res = null;
        if(isset($error_obj)){
            $message = ($this->language == "fa" ? (string)$error_obj["message_fa"] : (string)$error_obj["message_en"]);
            foreach ($this->params as $key => $value) {
                $message = Str::replaceFirst($key, $value, $message);
            }
            $json = array(
                "error_code"=>(string)$this->error_code,
                "error_field"=>(string)$this->error_field,
                "error_message"=>$message,
            );
            //$res = response()->json($json,(int)$error_obj["status"]);
            $res = response()->json($json, (int)$error_obj["status"], ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        else{
            $json = array(
                "error_code"=>"500",
                "error_message"=>"خطای نامشخص",
            );
            $res = response()->json($json, 500, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        }


        return $res;

    }

    public function get_c_response($message,$code,$log = ""){
        $json = array(
            "error_code"=>$code,
            "error_message"=>$message,
            "log"=>$log
        );
        $res = response()->json($json, $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        return $res;
    }

}
