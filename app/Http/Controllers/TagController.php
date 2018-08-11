<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use App\AnagraphTag;

class TagController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');

        $this->tag_type_dict = [
            'anagraph' => '方剂',
            'medicine' => '药物',
            'disease' => '方剂',
        ];
    }

    public function list()
    {
        $posts = Tag::where('is_del', '=', 0)
            ->orderBy('mt_id')
            ->paginate(config('medicine.posts_per_page'));



        foreach ($posts->getIterator() as $val) {
            $val->setAttribute('type', $this->tag_type_dict[$val->type]);
        }

        return view('tag.list', compact('posts'));
    }

    public function deleteTag(Request $request)
    {
        $int_mt_id = intval($request->get('mt_id'));

        if ($int_mt_id > 0) {
            // 1. 去掉关联关系
            $obj_tag = Tag::where('mt_id', '=', $int_mt_id)
                ->where('is_del', '=', 0)
                ->first();
            // 去掉标签和方剂的关联关系
            $obj_ana_tag = new AnagraphTag;
            $obj_ana_tag->deleteTag($int_mt_id);

            // 2.软删除
            Tag::where('mt_id', '=', $int_mt_id)
                ->where('is_del', '=', 0)
                ->update([
                    'is_del' => 1
                ]);

            return '1';
        }

        return '0';
    }

    public function showTag($mt_id)
    {
        $tag = Tag::where('mt_id', '=', $mt_id)->where('is_del', '=', 0)->first();
        $tag = $tag->toArray();

        $tag_count = Tag::where('mt_id', '<=', $mt_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($tag_count / intval(config('medicine.posts_per_page')));
        $tag['page_index'] = $page_index;

        $tag['type'] = $this->tag_type_dict[$tag['type']];

        return view('tag.detail', compact('tag'));
    }

    public function createTag(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $str_tag_name  = $input_data['tag_name'];
        $str_tag_desc  = isset($input_data['tag_desc']) ? $input_data['tag_desc'] : '';
        $str_tag_type  = $input_data['tag_type'];

        $obj_tag = Tag::where('name', '=', $str_tag_name)
            ->where('type', '=', $str_tag_type)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_tag)) {
            return '0';
        } else {
            $obj_new_tag = Tag::create([
                'name' => $str_tag_name,
                'type' => $str_tag_type,
                'desc' => $str_tag_desc,
                'create_time'  => date('Y-m-d H:i:s', time()),
                'update_time'  => date('Y-m-d H:i:s', time()),
            ]);
        }

        if (empty($obj_new_tag)) {
            return '0';
        } else {
            $int_mt_id = $obj_new_tag->mt_id;
        }

        return '1';
    }
}
