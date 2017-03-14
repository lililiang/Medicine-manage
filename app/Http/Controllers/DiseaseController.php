<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Disease;
use App\DiseaseAlias;

class DiseaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $posts = Disease::where('is_del', '=', 0)
        ->orderBy('md_id')
        ->paginate(config('medicine.posts_per_page'));

        return view('disease.list', compact('posts'));
    }

    public function showDisease($md_id)
    {
        $aliases = DiseaseAlias::where('md_id', '=', $md_id)->where('is_del', '=', 0)->get();
        $aliases = $aliases->toArray();

        $disease = Disease::where('md_id', '=', $md_id)->where('is_del', '=', 0)->first();
        $disease = $disease->toArray();

        $disease['consist'] = $aliases;

        $disease_count = Disease::where('md_id', '<=', $md_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($disease_count / intval(config('medicine.posts_per_page')));
        $disease['page_index'] = $page_index;

        return view('disease.detail', compact('disease'));
    }

    public function editDisease($md_id)
    {
        $aliases = DiseaseAlias::where('md_id', '=', $md_id)->where('is_del', '=', 0)->get();
        $aliases = $aliases->toArray();
        if (empty($aliases)) {
            $aliases = [
                [
                    'mda_id'        => 0,
                    'md_id'         => $md_id,
                    'disease_alias' => '',
                ],
            ];
        }

        $disease = Disease::where('md_id', '=', $md_id)->where('is_del', '=', 0)->first();
        $disease = $disease->toArray();

        $disease['consist'] = $aliases;

        return view('disease.edit', compact('disease'));
    }

    public function doEditDisease(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $int_md_id              = $input_data['md_id'];
        $str_disease_name       = $input_data['disease_name'];
        $str_disease_desc       = isset($input_data['disease_desc']) ? $input_data['disease_desc'] : '';
        $arr_diseases           = $input_data['diseases'];

        $obj_disease = Disease::where('md_id', '!=', $int_md_id)
            ->where('disease_name', '=', $str_disease_name)
            ->where('disease_desc', '=', $str_disease_desc)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_disease)) {
            return '0';
        } else {
            Disease::where('md_id', '=', $int_md_id)
                ->where('is_del', '=', 0)
                ->update([
                    'disease_name' => $str_disease_name,
                    'disease_desc' => $str_disease_desc,
                ]);
        }

        $composes = DiseaseAlias::where('md_id', '=', $int_md_id)->where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $delete_ids = [];
        foreach ($composes as $one_com) {
            $delete_ids[intval($one_com['mda_id'])] = intval($one_com['mda_id']);
        }

        foreach ($arr_diseases as $one_disease) {
            $int_mda_id         = isset($one_medicine['mda_id'])? intval($one_medicine['mac_id']) : 0;
            $str_disease_alias  = isset($one_disease['name']) ? $one_disease['name'] : '';

            if ($str_disease_alias != '') {
                if ($int_mda_id > 0) {
                    DiseaseAlias::where('mda_id', '=', $int_mda_id)
                        ->where('is_del', '=', 0)
                        ->update([
                            'disease_alias' => $str_disease_alias,
                        ]);

                    unset($delete_ids[$int_mda_id]);
                } else {
                    // 新增药物组成
                    DiseaseAlias::create([
                        'md_id'         => $int_md_id,
                        'disease_alias' => $str_disease_alias,
                    ]);
                }
            }
        }
        if (!empty($delete_ids)) {
            // 软删数据
            DiseaseAlias::where('md_id', '=', $int_md_id)
            ->whereIn('mda_id', array_keys($delete_ids))
            ->update([
                'is_del' => 1,
            ]);
        }

        return '1';
    }

    public function createDisease(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $str_disease_name       = $input_data['disease_name'];
        $str_disease_desc       = isset($input_data['disease_desc']) ? $input_data['disease_desc'] : '';
        $arr_diseases           = $input_data['diseases'];

        $obj_disease = Disease::where('disease_name', '=', $str_disease_name)
            ->where('disease_desc', '=', $str_disease_desc)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_disease)) {
            return '0';
        } else {
            $obj_new_disease = Disease::create([
                'disease_name' => $str_disease_name,
                'disease_desc' => $str_disease_desc,
            ]);
        }

        if (empty($obj_new_disease)) {
            return '0';
        } else {
            $int_md_id = $obj_new_disease->id;
        }

        foreach ($arr_diseases as $one_disease) {
            $str_disease_alias  = isset($one_disease['disease_alias']) ? $one_disease['disease_alias'] : '';
            // 新增药物组成
            $obj_res = DiseaseAlias::create([
                'md_id'         => $int_md_id,
                'disease_alias' => $str_disease_alias,
            ]);

            if (empty($obj_res)) {
                return '0';
            }
        }

        return '1';
    }
}
