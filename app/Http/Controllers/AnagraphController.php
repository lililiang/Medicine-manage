<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Anagraph;
use App\AnagraphCompose;
use App\AnagraphSimilarity;
use App\Medicament;

class AnagraphController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $posts = Anagraph::where('is_del', '=', 0)
            ->orderBy('ma_id')
            ->paginate(config('medicine.posts_per_page'));

        $anagraph_data = $posts->toArray();
        $anagraph_data = $anagraph_data['data'];

        $ma_ids = [];
        foreach ($anagraph_data as $one_ana) {
            $ma_ids[] = $one_ana['ma_id'];
        }

        $anagraph_composes = AnagraphCompose::whereIn('ma_id', $ma_ids)
            ->where(function($query){
                $query->where('standard_dosage', '=', 0)
                    ->orWhere('need_modify', '=', 1);
                })
            ->where('is_del', '=', 0)
            ->get();
        $anagraph_composes = $anagraph_composes->toArray();

        $need_dosage_composes = [];
        $need_modify = [];
        foreach ($anagraph_composes as $one_com) {
            if ($one_com['standard_dosage'] == 0) {
                $need_dosage_composes[$one_com['ma_id']] = 1;
            }

            if ($one_com['need_modify'] == 1) {
                $need_modify[$one_com['ma_id']] = 1;
            }
        }

        // add syndrome data
        foreach ($posts->getIterator() as $val) {
            if (isset($need_dosage_composes[$val->ma_id])) {
                $val->setAttribute('need_dosage', true);
            }

            if (isset($need_modify[$val->ma_id])) {
                $val->setAttribute('need_modify', true);
            }
        }

        return view('anagraph.list', compact('posts'));
    }

    public function showAnagraph($ma_id)
    {
        $composes = AnagraphCompose::where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $ids = [];
        foreach ($composes as $one_com) {
            $ids[] = intval($one_com['mm_id']);
        }

        $medicines = Medicament::whereIn('mm_id', $ids)->get();
        $medicines = $medicines->toArray();

        $medicines_tmp = [];
        foreach ($medicines as $one_med) {
            $medicines_tmp[intval($one_med['mm_id'])] = $one_med['medicine_name'];
        }

        foreach ($composes as &$one_com) {
            $one_com['medicine_name'] = $medicines_tmp[intval($one_com['mm_id'])];
            $usage = json_decode($one_com['usage'], true);
            $one_com['usage'] = implode(',', $usage);
        }

        $anagraph = Anagraph::where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->first();
        $anagraph = $anagraph->toArray();

        $anagraph['consist'] = $composes;

        $anagraph_count = Anagraph::where('ma_id', '<=', $ma_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($anagraph_count / intval(config('medicine.posts_per_page')));
        $anagraph['page_index'] = $page_index;

        $similarities = AnagraphSimilarity::where('src_id', '=', $ma_id)
            ->where('is_del', '=', 0)
            ->orderBy('similarity', 'desc')
            ->take(5)
            ->get();

        $ma_ids = [];
        $similarities = $similarities->toArray();
        foreach ($similarities as $one) {
            $ma_ids[] = $one['des_id'];
        }

        $anagraph_similarities = Anagraph::whereIn('ma_id', $ma_ids)->where('is_del', '=', 0)->get();
        $anagraph_similarities = $anagraph_similarities->toArray();

        $anagraph_tmp = [];
        foreach ($anagraph_similarities as $one_sim) {
            $anagraph_tmp[$one_sim['ma_id']] = $one_sim['anagraph_name'];
        }

        foreach ($similarities as &$one_similarity) {
            $one_similarity['anagraph_name'] = $anagraph_tmp[$one_similarity['des_id']];
        }

        $anagraph['similarities'] = $similarities;

        return view('anagraph.detail', compact('anagraph'));
    }

    public function editAnagraph($ma_id)
    {
        $composes = AnagraphCompose::where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $ids = [];
        foreach ($composes as $one_com) {
            $ids[] = intval($one_com['mm_id']);
        }

        $medicines = Medicament::whereIn('mm_id', $ids)->get();
        $medicines = $medicines->toArray();

        $medicines_tmp = [];
        foreach ($medicines as $one_med) {
            $medicines_tmp[intval($one_med['mm_id'])] = $one_med['medicine_name'];
        }

        foreach ($composes as &$one_com) {
            $one_com['medicine_name'] = $medicines_tmp[intval($one_com['mm_id'])];
            $usage = json_decode($one_com['usage'], true);
            $one_com['usage'] = implode(',', $usage);
        }

        $anagraph = Anagraph::where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->first();
        $anagraph = $anagraph->toArray();

        $anagraph['consist'] = $composes;

        return view('anagraph.edit', compact('anagraph'));
    }

    public function doEditAnagraph(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $int_ma_id              = $input_data['ma_id'];
        $str_anagraph_name      = $input_data['anagraph_name'];
        $str_anagraph_origin    = $input_data['anagraph_origin'];
        $arr_medicines          = $input_data['medicines'];

        $obj_anagraph = Anagraph::where('ma_id', '!=', $int_ma_id)
            ->where('anagraph_name', '=', $str_anagraph_name)
            ->where('anagraph_origin', '=', $str_anagraph_origin)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_anagraph)) {
            return '0';
        } else {
            Anagraph::where('ma_id', '=', $int_ma_id)
                ->where('is_del', '=', 0)
                ->update([
                    'anagraph_name'     => $str_anagraph_name,
                    'anagraph_origin'   => $str_anagraph_origin,
                ]);
        }

        $composes = AnagraphCompose::where('ma_id', '=', $int_ma_id)->where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $delete_ids = [];
        foreach ($composes as $one_com) {
            $delete_ids[intval($one_com['mac_id'])] = intval($one_com['mac_id']);
        }

        foreach ($arr_medicines as $one_medicine) {
            if (is_array($one_medicine) && count($one_medicine) > 0) {
                $int_mac_id         = isset($one_medicine['mac_id'])? intval($one_medicine['mac_id']) : 0;
                $int_mm_id          = isset($one_medicine['mm_id'])? intval($one_medicine['mm_id']) : 0;
                $str_medicine_name  = $one_medicine['name'];
                $str_dosage         = isset($one_medicine['dosage'])? $one_medicine['dosage'] : '';
                $standard_dosage    = isset($one_medicine['standard_dosage'])? floatval($one_medicine['standard_dosage']) : 0.0;
                $tmp_usage          = explode(',', strval($one_medicine['usage']));
                $str_usage          = json_encode($tmp_usage);
                $int_need_modify    = isset($one_medicine['need_modify'])? $one_medicine['need_modify'] : 0;

                $obj_medicine = Medicament::where('medicine_name', '=', $str_medicine_name)
                    ->where('is_del', '=', 0)
                    ->first();
                if (empty($obj_medicine)) {
                    // 新增药物
                    $obj_new_medicine = Medicament::create(['medicine_name' => $str_medicine_name]);
                    if (isset($obj_new_medicine->id)) {
                        if ($int_mac_id > 0) {
                            AnagraphCompose::where('mac_id', '=', $int_mac_id)
                                ->where('is_del', '=', 0)
                                ->update([
                                    'mm_id'             => intval($obj_new_medicine->id),
                                    'dosage'            => $str_dosage,
                                    'standard_dosage'   => $standard_dosage,
                                    'usage'             => $str_usage,
                                    'need_modify'       => $int_need_modify
                                ]);
                        } else {
                            // 新增药物组成
                            AnagraphCompose::create([
                                'ma_id'             => $int_ma_id,
                                'mm_id'             => intval($obj_new_medicine->id),
                                'dosage'            => $str_dosage,
                                'standard_dosage'   => $standard_dosage,
                                'usage'             => $str_usage,
                                'need_modify'       => $int_need_modify
                            ]);
                        }
                    } else {
                        return '0';
                    }
                } else {
                    $arr_medicine = $obj_medicine->toArray();
                    if ($int_mac_id > 0) {
                        if ($int_mm_id != $arr_medicine['mm_id']) {
                            AnagraphCompose::where('mac_id', '=', $int_mac_id)
                                ->where('is_del', '=', 0)
                                ->update([
                                    'mm_id'             => $arr_medicine['mm_id'],
                                    'dosage'            => $str_dosage,
                                    'standard_dosage'   => $standard_dosage,
                                    'usage'             => $str_usage,
                                    'need_modify'       => $int_need_modify
                                ]);
                        } else {
                            AnagraphCompose::where('mac_id', '=', $int_mac_id)
                                ->where('is_del', '=', 0)
                                ->update([
                                    'dosage'            => $str_dosage,
                                    'standard_dosage'   => $standard_dosage,
                                    'usage'             => $str_usage,
                                    'need_modify'       => $int_need_modify
                                ]);
                        }
                    } else {
                        // 新增药物组成
                        AnagraphCompose::create([
                            'ma_id'             => $int_ma_id,
                            'mm_id'             => intval($arr_medicine['mm_id']),
                            'dosage'            => $str_dosage,
                            'standard_dosage'   => $standard_dosage,
                            'usage'             => $str_usage,
                            'need_modify'       => $int_need_modify
                        ]);
                    }
                }

                if ($int_mac_id > 0) {
                    unset($delete_ids[$int_mac_id]);
                }
            }
        }

        // 软删数据
        AnagraphCompose::where('ma_id', '=', $int_ma_id)
            ->whereIn('mac_id', array_keys($delete_ids))
            ->update([
                'is_del' => 1,
            ]);

        return '1';
    }

    public function createAnagraph(Request $request)
    {
        // 保存的逻辑
        $input_data = $request->get('data');

        $str_anagraph_name      = $input_data['anagraph_name'];
        $str_anagraph_origin    = $input_data['anagraph_origin'];
        $arr_medicines          = $input_data['medicines'];

        $obj_anagraph = Anagraph::where('anagraph_name', '=', $str_anagraph_name)
            ->where('anagraph_origin', '=', $str_anagraph_origin)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_anagraph)) {
            return '0';
        } else {
            $obj_new_anagraph = Anagraph::create([
                'anagraph_name'     => $str_anagraph_name,
                'anagraph_origin'   => $str_anagraph_origin,
                'indexs'            => '[]',
            ]);
        }

        if (empty($obj_new_anagraph)) {
            return '0';
        } else {
            $int_ma_id = $obj_new_anagraph->id;
        }

        foreach ($arr_medicines as $one_medicine) {
            $str_medicine_name  = $one_medicine['name'];
            $str_dosage         = isset($one_medicine['dosage']) ? $one_medicine['dosage'] : '';
            $standard_dosage    = isset($one_medicine['standard_dosage']) ? floatval($one_medicine['standard_dosage']) : 0.0;
            $tmp_usage          = explode(',', strval($one_medicine['usage']));
            $str_usage          = json_encode($tmp_usage);

            $obj_medicine = Medicament::where('medicine_name', '=', $str_medicine_name)
                ->where('is_del', '=', 0)
                ->first();
            if (empty($obj_medicine)) {
                // 新增药物
                $obj_new_medicine = Medicament::create(['medicine_name' => $str_medicine_name]);
                if (!isset($obj_new_medicine->id)) {
                    return '0';
                } else {
                    $int_mm_id = $obj_new_medicine->id;
                }
            } else {
                $int_mm_id = $obj_medicine->mm_id;
            }

            // 新增药物组成
            $obj_res = AnagraphCompose::create([
                'ma_id'             => $int_ma_id,
                'mm_id'             => $int_mm_id,
                'dosage'            => $str_dosage,
                'standard_dosage'   => $standard_dosage,
                'usage'             => $str_usage,
            ]);

            if (empty($obj_res)) {
                return '0';
            }
        }

        return '1';
    }

    public function calculateSimilarity()
    {
        $anagraphs = Anagraph::where('is_del', '=', 0)->get();
        $anagraphs = $anagraphs->toArray();

        $anagraphs_tmp = [];
        foreach ($anagraphs as $one_ana) {
            $anagraphs_tmp[intval($one_ana['ma_id'])] = $one_ana['anagraph_name'];
        }

        $medicines = Medicament::where('is_del', '=', 0)->get();
        $medicines = $medicines->toArray();

        $medicines_tmp = [];
        foreach ($medicines as $one_med) {
            $medicines_tmp[intval($one_med['mm_id'])] = $one_med['medicine_name'];
        }

        $composes = AnagraphCompose::where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $anagraph_data = [];
        foreach ($composes as $one_com) {
            $ma_id = intval($one_com['ma_id']);
            $mm_id = intval($one_com['mm_id']);

            $anagraph_data[$ma_id][] = $medicines_tmp[$mm_id];
        }

        foreach ($anagraph_data as &$ana_medicines) {
            $ana_medicines = array_values(array_unique($ana_medicines));
        }

        AnagraphSimilarity::where('is_del', '=', 0)->delete();

        $ids = [];
        foreach ($anagraph_data as $src_id => $src_medicines) {
            $ids[] = $src_id;
            foreach ($anagraph_data as $des_id => $des_medicines) {
                if (!in_array($des_id, $ids)) {
                    $merge_medicines = array_merge($src_medicines, $des_medicines);
                    $merge_medicines = array_values(array_unique($merge_medicines));

                    if (!empty($merge_medicines)) {
                        $intersect_medicines    = array_intersect($src_medicines, $des_medicines);
                        $jaccard_similarity     = count($intersect_medicines) / count($merge_medicines);

                        AnagraphSimilarity::create([
                            'src_id'        => $src_id,
                            'des_id'        => $des_id,
                            'similarity'    => $jaccard_similarity
                        ]);
                    }
                }
            }
        }

        return '1';
    }
}
