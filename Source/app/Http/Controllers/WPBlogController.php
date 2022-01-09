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

class WPBlogController extends APIController
{





    public function get_posts(Request $request)
    {
        $this->getParameters($request);

        $messages = [
            'language'          => '10',
        ];
        $validator = Validator::make($this->raw, [
            'language.*'            => 'required|string|min:1|max:150',
        ], $messages);


        if ($validator->fails()) {
            return (new response_error($validator->errors()))->get_response();
        }

        if(is_numeric($this->raw['category_id'])) {
            $wp_categories = DB::table('wp_term_relationships')
                ->where('term_taxonomy_id', '=', $this->raw['category_id'])
                ->get("object_id");
        }

        $wp_posts_db = DB::table('wp_posts');
        $wp_posts_db = $wp_posts_db->leftJoin('wp_postmeta', function ($join) {
                $join->on('wp_posts.id', '=', 'wp_postmeta.post_id')
                    ->where('meta_key', '=', '_thumbnail_id');
            });
            //->leftJoin('(SELECT * FROM wp_postmeta WHERE meta_key="_thumbnail_id") pm', 'p.id', '=', 'pm.post_id')
        $wp_posts_db = $wp_posts_db->where('wp_posts.post_type','=','post');
        $wp_posts_db = $wp_posts_db->where('wp_posts.post_status','=','publish');
        $wp_posts_db = $wp_posts_db->where('post_name','LIKE', '%' . $this->raw['language']);

        if(is_numeric($this->raw['category_id'])) {
            $wp_categories = DB::table('wp_term_relationships')
                ->where('term_taxonomy_id', '=', $this->raw['category_id'])
                ->get("object_id")->toArray();

            $post_in_categories = array();
            foreach ($wp_categories as $item){
                $post_in_categories[] = $item->object_id;
            }

            $wp_posts_db = $wp_posts_db->whereIn('wp_posts.ID', $post_in_categories);

        }

        $wp_posts_db = $wp_posts_db->orderBy('post_modified', 'desc');


        $wp_posts = $wp_posts_db->get(array("ID","post_author","post_modified","post_content","post_title","post_name","comment_count","meta_value"));

        if($wp_posts) {
            $resultArr = array();
            foreach($wp_posts as $post) {
                $post = json_decode(json_encode($post), true);

                $post['attachments'] = array();

                if(isset($post['meta_value'])) {
                    $wp_attachments = DB::select("SELECT p.guid,p.post_title,pm.meta_value FROM wp_posts p INNER JOIN wp_postmeta pm ON p.ID=pm.post_id WHERE meta_key='_wp_attachment_metadata' AND post_type='attachment' AND id = :id", ['id' => $post['meta_value']]);
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

