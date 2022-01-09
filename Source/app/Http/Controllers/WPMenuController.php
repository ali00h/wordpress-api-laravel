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




use Response;

class WPMenuController extends APIController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function get_urls(Request $request)
    {
        $this->getParameters($request);

        $messages = [
            'menu_id.*'          => '10',
        ];
        $validator = Validator::make($this->raw, [
            'menu_id'            => 'required|numeric',
        ], $messages);


        if ($validator->fails()) {
            return (new response_error($validator->errors()))->get_response();
        }

        $wp_posts = DB::table('wp_posts')
            ->join('wp_term_relationships', 'wp_posts.ID', '=', 'wp_term_relationships.object_id')
            ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->where('wp_postmeta.meta_key','=','_menu_item_url')
            ->where('wp_posts.post_status','=','publish')
            ->where('term_taxonomy_id','=', $this->raw['menu_id'])
            ->orderBy('menu_order', 'desc')
            ->get(array("ID","post_title","meta_value"));

        if($wp_posts) {

            return $this->response($wp_posts,200);
        }else{
            return (new response_error())->get_c_response("404 not found!",404);
        }


    }
}

