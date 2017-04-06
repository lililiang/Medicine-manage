<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TcmSyndrome;
use App\TcmSyndromeAlias;

class SyndromeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        $posts = TcmSyndrome::where('is_del', '=', 0)
            ->orderBy('mts_id')
            ->paginate(config('medicine.posts_per_page'));

        return view('syndrome.list', compact('posts'));
    }

    public function deleteSyndrome(Request $request)
    {
        $int_mts_id = intval($request->get('mts_id'));

        if ($int_mts_id > 0) {
            // 1. 去掉关联关系
            $obj_syndrome = TcmSyndrome::where('mts_id', '=', $int_mts_id)
                ->where('is_del', '=', 0)
                ->first();
            // 去掉证候别名的关联关系
            $obj_syndrome->alias()->detach($int_mts_id);
            // 去掉症状和证候的关联关系
            $obj_syndrome->disease()->detach($int_mts_id);

            // 2.软删除
            TcmSyndrome::where('mts_id', '=', $int_mts_id)
                ->where('is_del', '=', 0)
                ->update([
                    'is_del' => 1
                ]);

            return '1';
        }

        return '0';
    }

    public function showSyndrome($mts_id)
    {
        $syndrome   = TcmSyndrome::where('mts_id', '=', $mts_id)->where('is_del', '=', 0)->first();
        $aliases    = $syndrome->alias()->get()->toArray();
        $syndrome   = $syndrome->toArray();

        $syndrome['alias']       = $aliases;

        $syndrome_count = TcmSyndrome::where('mts_id', '<=', $mts_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($syndrome_count / intval(config('medicine.posts_per_page')));
        $syndrome['page_index'] = $page_index;

        return view('syndrome.detail', compact('syndrome'));
    }

    public function editSyndromeAlias($mts_id)
    {
        $syndrome   = TcmSyndrome::where('mts_id', '=', $mts_id)->where('is_del', '=', 0)->first();
        $aliases    = $syndrome->alias()->get()->toArray();
        $syndrome   = $syndrome->toArray();

        if (empty($aliases)) {
            $aliases = [
                [
                    'mtsa_id'           => 0,
                    'mts_id'            => $mts_id,
                    'syndrome_alias'    => '',
                ],
            ];
        }

        $syndrome['alias'] = $aliases;

        return view('syndrome.edit', compact('syndrome'));
    }

    public function doEditSyndromeAlias(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $int_mts_id             = $input_data['mts_id'];
        $str_syndrome_name      = $input_data['syndrome_name'];
        $str_syndrome_desc      = isset($input_data['syndrome_desc']) ? $input_data['syndrome_desc'] : '';
        $arr_aliases            = $input_data['syndrome_alias'];

        $obj_syndrome = TcmSyndrome::where('mts_id', '!=', $int_mts_id)
            ->where('syndrome_name', '=', $str_syndrome_name)
            ->where('syndrome_desc', '=', $str_syndrome_desc)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_syndrome)) {
            return '0';
        } else {
            TcmSyndrome::where('mts_id', '=', $int_mts_id)
                ->where('is_del', '=', 0)
                ->update([
                    'syndrome_name' => $str_syndrome_name,
                    'syndrome_desc' => $str_syndrome_desc,
                ]);
        }

        $syndrome = TcmSyndrome::where('mts_id', '=', $int_mts_id)->where('is_del', '=', 0)->first();
        $aliases = $syndrome->alias()->get()->toArray();

        $add_ids = [];
        foreach ($arr_aliases as $one_alias) {
            $str_alias_name = isset($one_alias['name']) ? $one_alias['name'] : '';

            if ($str_alias_name != '') {

                $tmp_alias = TcmSyndromeAlias::where('syndrome_alias', '=', $str_alias_name)
                    ->where('is_del', '=', 0)
                    ->first();

                if (isset($tmp_alias['mtsa_id'])) {
                    $add_ids[] = $tmp_alias['mtsa_id'];
                } else {
                    // 新增药物组成
                    $obj_syndrome = TcmSyndromeAlias::create([
                        'syndrome_alias'    => $str_alias_name,
                        'create_time'       => date('Y-m-d H:i:s', time()),
                        'update_time'       => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_ids[] = $obj_syndrome->mtsa_id;
                }
            }
        }

        if (!empty($add_ids)) {
            $syndrome->alias()->sync($add_ids);
        }

        return '1';
    }

    public function createSyndrome(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $str_syndrome_name  = $input_data['syndrome_name'];
        $str_syndrome_desc  = isset($input_data['syndrome_desc']) ? $input_data['syndrome_desc'] : '';
        $arr_aliases        = $input_data['alias'];

        $obj_syndrome = TcmSyndrome::where('syndrome_name', '=', $str_syndrome_name)
            ->where('syndrome_desc', '=', $str_syndrome_desc)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_syndrome)) {
            return '0';
        } else {
            $obj_new_syndrome = TcmSyndrome::create([
                'syndrome_name' => $str_syndrome_name,
                'syndrome_desc' => $str_syndrome_desc,
                'create_time'  => date('Y-m-d H:i:s', time()),
                'update_time'  => date('Y-m-d H:i:s', time()),
            ]);
        }

        if (empty($obj_new_syndrome)) {
            return '0';
        } else {
            $int_mts_id = $obj_new_syndrome->mts_id;
        }

        $add_alias_ids = [];
        foreach ($arr_aliases as $one_alias) {
            $str_syndrome_alias  = isset($one_alias['syndrome_alias']) ? $one_alias['syndrome_alias'] : '';
            if ($str_syndrome_alias != '') {
                // 新增药物组成
                $obj_alias = TcmSyndromeAlias::where('syndrome_alias', '=', $str_syndrome_alias)
                    ->where('is_del', '=', 0)
                    ->first();

                if (!empty($obj_alias)) {
                    $add_alias_ids[] = $obj_alias->mtsa_id;
                } else {
                    $obj_new_alias = TcmSyndromeAlias::create([
                        'syndrome_alias' => $str_syndrome_alias,
                        'create_time'    => date('Y-m-d H:i:s', time()),
                        'update_time'    => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_alias_ids[] = $obj_new_alias->mtsa_id;
                }
            }
        }

        if (!empty($add_alias_ids)) {
            $obj_new_disease->alias()->attach($add_alias_ids);
        }

        return '1';
    }
}
