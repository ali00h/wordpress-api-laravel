<?php

namespace App\Http\Controllers;

use App\Models\response_error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use View;
use Mail;



use Response;

class WPFormController extends APIController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function send_message(Request $request)
    {
        $this->getParameters($request);

        $messages = [
            'name.*'          => '10',
            'email.*'         => '10',
            'subject.*'       => '10',
            'message.*'       => '10',
        ];
        $validator = Validator::make($this->raw, [
            'name'            => 'required|string|min:2|max:200',
            'email'           => 'required|email|min:4|max:150',
            'subject'         => 'required|string|min:4|max:200',
            'message'         => 'required|string|min:4|max:400',
        ], $messages);

        $locale = (isset($this->raw["locale"]) ? $this->raw["locale"] : "fa");

        if ($validator->fails()) {
            return (new response_error($validator->errors(),array(),$locale))->get_response();
        }

        $message_code = 100;

        $messages = array(
            100 => array("status"=>200,"message_fa"=>"پیام با موفقیت ارسال شد." , "message_en"=>"Message Sent Successfully!"),
            110 => array("status"=>200,"message_fa"=>"در ارسال پیام مشکلی بوجود آمده لطفا مجددا تلاش کنید." , "message_en"=>"Error in sending message!"),

        );




        $data = array('name'=>(string)$this->raw["name"],'email'=>(string)$this->raw["email"],'subject'=>(string)$this->raw["subject"],'message_body'=>(string)$this->raw["message"]);

        try {
            Mail::send(['text'=>'mail/contactus'], $data, function($message) {
                $message->to('info@rabinvira.com', 'RabinVira')->subject('Contact US');
                $message->from('no-reply@rabinvira.com','RabinVira');
            });
        } catch (\Exception $e) { $message_code = 110; }


        return (new response_error())->get_c_response($messages[$message_code]["message_" . $locale],$messages[$message_code]["status"]);

    }
}

