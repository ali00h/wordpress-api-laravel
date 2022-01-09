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

class WPGalleryController extends APIController
{






    public function get_images(Request $request)
    {
        $this->getParameters($request);

        $messages = [
            'gallery_id.*'          => '10',
        ];
        $validator = Validator::make($this->raw, [
            'gallery_id'            => 'required|numeric',
        ], $messages);


        if ($validator->fails()) {
            return (new response_error($validator->errors()))->get_response();
        }

        $wp_images = DB::table('wp_postmeta')
            ->where('meta_key','=','foogallery_attachments')
            ->where('post_id','=',$this->raw['gallery_id'])
            ->get();

        //return unserialize($wp_images[0]->meta_value);

        $wp_posts = DB::table('wp_posts')
            /*
            ->leftJoin('wp_postmeta', function ($join) {
                $join->on('wp_posts.id', '=', 'wp_postmeta.post_id')
                    ->where('meta_key', '=', '_thumbnail_id');
            })
            */
            //->leftJoin('(SELECT * FROM wp_postmeta WHERE meta_key="_thumbnail_id") pm', 'p.id', '=', 'pm.post_id')
            ->where('wp_posts.post_type','=','attachment')
            ->where('wp_posts.post_status','=','inherit')
            ->whereIn('wp_posts.ID', unserialize($wp_images[0]->meta_value))
            ->orderBy('post_modified', 'desc')
            ->get(array("ID","post_author","post_modified","post_content","post_title","post_name","comment_count"));

        if($wp_posts) {

            $resultArr = array();
            foreach($wp_posts as $post) {
                $post = json_decode(json_encode($post), true);

                $post['attachments'] = array();

                if(isset($post['ID'])) {
                    $wp_attachments = DB::select("SELECT p.guid,p.post_title,pm.meta_value FROM wp_posts p INNER JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE meta_key='_wp_attachment_metadata' AND post_type='attachment' AND id = :id", ['id' => $post['ID']]);
                    foreach ($wp_attachments as $att) {
                        $meta_value = unserialize($att->meta_value);
                        $post['attachments'][] = array("path" => explode(basename($att->guid), $att->guid)[0], "sizes" => $meta_value["sizes"]);
                    }
                }

                $resultArr[] = $post;
            }

            return $this->response($resultArr,200);
        }else{
            return (new response_error())->get_c_response("404 not found!",404);
        }


    }

}

