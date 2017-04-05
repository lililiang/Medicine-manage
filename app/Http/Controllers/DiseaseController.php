<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Disease;
use App\DiseaseAlias;
use App\TcmSyndrome;
use App\TcmSyndromeDiseaseRelation;


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
            ->where('md_id', '>', 11)
            ->orderBy('md_id')
            ->paginate(config('medicine.posts_per_page'));

        // get syndrome data
        $disease_data = $posts->toArray();
        if (isset($disease_data['data'])) {
            $disease_data = $disease_data['data'];

            $md_ids = [];

            foreach ($disease_data as $one_disease) {
                $md_ids[] = intval($one_disease['md_id']);
            }

            $syndrome_relas = TcmSyndromeDiseaseRelation::whereIn('md_id', $md_ids)->get();
            $syndrome_relas = $syndrome_relas->toArray();

            $mts_ids                = [];
            $disease_syndrome_ids   = [];
            foreach ($syndrome_relas as $one_syndrome_rela) {
                $disease_syndrome_ids[$one_syndrome_rela['md_id']][] = $one_syndrome_rela['mts_id'];
                $mts_ids[$one_syndrome_rela['mts_id']] = 1;
            }

            $mts_ids = array_keys($mts_ids);

            $syndrome_data = TcmSyndrome::whereIn('mts_id', $mts_ids)->get();
            $syndrome_data = $syndrome_data->toArray();

            $syndromes  = [];
            foreach ($syndrome_data as $one_syndrome) {
                $syndromes[$one_syndrome['mts_id']] = $one_syndrome['syndrome_name'];
            }

            $disease_syndrome_data = [];
            foreach ($disease_syndrome_ids as $md_id => $mts_ids) {
                $tmp_syndromes = [];
                foreach ($mts_ids as $mts_id) {
                    if (isset($syndromes[$mts_id])) {
                        $tmp_syndromes[] = $syndromes[$mts_id];
                    }
                }

                $disease_syndrome_data[$md_id] = implode(',', $tmp_syndromes);
            }
        }

        // add syndrome data
        foreach ($posts->getIterator() as $val) {
            if (isset($disease_syndrome_data[$val->md_id])) {
                $val->setAttribute('syndromes', $disease_syndrome_data[$val->md_id]);
            }
        }

        return view('disease.list', compact('posts'));
    }

    public function showDisease($md_id)
    {
        $aliases = Disease::where('is_del', '=', 0)->find($md_id)->alias()->get();
        $aliases = $aliases->toArray();

        $disease    = Disease::where('md_id', '=', $md_id)->where('is_del', '=', 0)->first();
        $syndromes  = $disease->tcmSyndromes()->get()->toArray();
        $disease    = $disease->toArray();

        $disease['alias']       = $aliases;
        $disease['syndromes']   = $syndromes;

        $disease_count = Disease::where('md_id', '<=', $md_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($disease_count / intval(config('medicine.posts_per_page')));
        $disease['page_index'] = $page_index;

        return view('disease.detail', compact('disease'));
    }

    public function editDiseaseAlias($md_id)
    {
        $disease = Disease::where('md_id', '=', $md_id)->where('is_del', '=', 0)->first();
        $aliases = $disease->alias()->get()->toArray();
        $disease = $disease->toArray();

        if (empty($aliases)) {
            $aliases = [
                [
                    'mda_id'        => 0,
                    'md_id'         => $md_id,
                    'disease_alias' => '',
                ],
            ];
        }

        $disease['alias'] = $aliases;

        return view('disease.edit', compact('disease'));
    }

    public function doEditDiseaseAlias(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $int_md_id              = $input_data['md_id'];
        $str_disease_name       = $input_data['disease_name'];
        $str_disease_desc       = isset($input_data['disease_desc']) ? $input_data['disease_desc'] : '';
        $arr_aliases            = $input_data['diseases'];

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

        $disease = Disease::where('md_id', '=', $int_md_id)->where('is_del', '=', 0)->first();
        $aliases = $disease->alias()->get()->toArray();

        $delete_ids = [];
        foreach ($aliases as $one) {
            $delete_ids[intval($one['mda_id'])] = intval($one['mda_id']);
        }
        $disease->alias()->detach($delete_ids);


        $add_ids = [];
        foreach ($arr_aliases as $one_alias) {
            $str_alias_name = isset($one_alias['name']) ? $one_alias['name'] : '';

            if ($str_alias_name != '') {

                $tmp_alias = DiseaseAlias::where('disease_alias', '=', $str_alias_name)
                    ->where('is_del', '=', 0)
                    ->first();
                if (isset($tmp_alias['mda_id'])) {
                    $add_ids[] = $tmp_alias['mda_id'];
                } else {
                    // 新增药物组成
                    $obj_syndrome = DiseaseAlias::create([
                        'disease_alias' => $str_alias_name,
                        'create_time'   => date('Y-m-d H:i:s', time()),
                        'update_time'   => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_ids[] = $obj_syndrome->mda_id;
                }
            }
        }

        if (!empty($add_ids)) {
            $disease->alias()->attach($add_ids);
        }

        return '1';
    }

    public function editDiseaseSyndromes($md_id)
    {
        $disease    = Disease::where('md_id', '=', $md_id)->where('is_del', '=', 0)->first();
        $syndromes  = $disease->tcmSyndromes()->get()->toArray();
        $disease    = $disease->toArray();

        if (empty($syndromes)) {
            $syndromes = [
                [
                    'mts_id'        => 0,
                    'syndrome_name' => '',
                    'syndrome_desc' => '',
                ],
            ];
        }

        $disease['syndromes'] = $syndromes;

        return view('disease.edit', compact('disease'));
    }

    public function doEditDiseaseSyndromes(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $int_md_id              = $input_data['md_id'];
        $str_disease_name       = $input_data['disease_name'];
        $str_disease_desc       = isset($input_data['disease_desc']) ? $input_data['disease_desc'] : '';
        $arr_syndromes          = $input_data['diseases'];

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

        $disease    = Disease::where('md_id', '=', $int_md_id)->where('is_del', '=', 0)->first();
        $syndromes  = $disease->tcmSyndromes()->get()->toArray();

        $delete_ids = [];
        foreach ($syndromes as $one) {
            $delete_ids[intval($one['mts_id'])] = intval($one['mts_id']);
        }
        $disease->tcmSyndromes()->detach($delete_ids);


        $add_ids = [];
        foreach ($arr_syndromes as $one_syndrome) {
            $str_syndrome_name = isset($one_syndrome['name']) ? $one_syndrome['name'] : '';
            $str_syndrome_desc = isset($one_syndrome['desc']) ? $one_syndrome['desc'] : '';

            if ($str_syndrome_name != '') {

                $tmp_syndrome = TcmSyndrome::where('syndrome_name', '=', $str_syndrome_name)
                    ->where('is_del', '=', 0)
                    ->first();
                if (isset($tmp_syndrome['mts_id'])) {
                    $add_ids[] = $tmp_syndrome['mts_id'];
                } else {
                    // 新增药物组成
                    $obj_syndrome = TcmSyndrome::create([
                        'syndrome_name' => $str_syndrome_name,
                        'syndrome_desc' => $str_syndrome_desc,
                        'create_time'   => date('Y-m-d H:i:s', time()),
                        'update_time'   => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_ids[] = $obj_syndrome->mts_id;
                }
            }
        }

        if (!empty($add_ids)) {
            $disease->tcmSyndromes()->attach($add_ids);
        }

        return '1';
    }

    public function createDisease(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $str_disease_name       = $input_data['disease_name'];
        $str_disease_desc       = isset($input_data['disease_desc']) ? $input_data['disease_desc'] : '';
        $arr_aliases            = $input_data['alias'];
        $arr_syndromes          = $input_data['syndrome'];

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
                'create_time'  => date('Y-m-d H:i:s', time()),
                'update_time'  => date('Y-m-d H:i:s', time()),
            ]);
        }

        if (empty($obj_new_disease)) {
            return '0';
        } else {
            $int_md_id = $obj_new_disease->md_id;
        }

        $add_alias_ids = [];
        foreach ($arr_aliases as $one_alias) {
            $str_disease_alias  = isset($one_alias['disease_alias']) ? $one_alias['disease_alias'] : '';
            if ($str_disease_alias != '') {
                // 新增药物组成
                $obj_alias = DiseaseAlias::where('disease_alias', '=', $str_disease_alias)
                    ->where('is_del', '=', 0)
                    ->first();

                if (!empty($obj_alias)) {
                    $add_alias_ids[] = $obj_alias->mda_id;
                } else {
                    $obj_new_alias = DiseaseAlias::create([
                        'disease_alias' => $str_disease_alias,
                        'create_time'   => date('Y-m-d H:i:s', time()),
                        'update_time'   => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_alias_ids[] = $obj_new_alias->mda_id;
                }
            }
        }

        if (!empty($add_alias_ids)) {
            $obj_new_disease->alias()->attach($add_alias_ids);
        }

        $add_syndrome_ids = [];
        foreach ($arr_syndromes as $one_syndrome) {
            $str_syndrome_name  = isset($one_syndrome['name']) ? $one_syndrome['name'] : '';
            $str_syndrome_desc  = isset($one_syndrome['desc']) ? $one_syndrome['desc'] : '';

            if ($str_syndrome_name != '') {
                // 新增药物组成
                $obj_syndrome = TcmSyndrome::where('syndrome_name', '=', $str_syndrome_name)
                    ->where('is_del', '=', 0)
                    ->first();

                if (!empty($obj_syndrome)) {
                    $add_syndrome_ids[] = $obj_syndrome->mts_id;
                } else {
                    $obj_new_syndrome = TcmSyndrome::create([
                        'syndrome_name' => $str_syndrome_name,
                        'syndrome_desc' => $str_syndrome_desc,
                        'create_time'   => date('Y-m-d H:i:s', time()),
                        'update_time'   => date('Y-m-d H:i:s', time()),
                    ]);

                    $add_syndrome_ids[] = $obj_new_syndrome->mts_id;
                }
            }
        }

        if (!empty($add_syndrome_ids)) {
            $obj_new_disease->tcmSyndromes()->attach($add_syndrome_ids);
        }

        return '1';
    }
}
