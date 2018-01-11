<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Redirect;
use App\Anagraph;
use App\Disease;
use App\AnagraphCompose;
use App\AnagraphSourceRelation;
use App\AnagraphSimilarity;
use App\Medicament;
use App\MedicineDataSource;
use App\PrescriptionDataSource;

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

        $mm_ids = [];
        foreach ($anagraph_composes as $one_ana_com) {
            $mm_ids[intval($one_ana_com['mm_id'])] = 1;
        }
        $mm_ids = array_keys($mm_ids);

        $medicines = Medicament::whereIn('mm_id', $mm_ids)->where('is_missing', '=', 1)->get();
        $medicines = $medicines->toArray();

        $medicine_missing_ids = [];
        foreach ($medicines as $one_med) {
            $medicine_missing_ids[] = intval($one_med['mm_id']);
        }

        $need_dosage_composes = [];
        $need_modify = [];
        $need_find = [];
        foreach ($anagraph_composes as $one_com) {
            if ($one_com['standard_dosage'] == 0) {
                $need_dosage_composes[$one_com['ma_id']] = 1;
            }

            if ($one_com['need_modify'] == 1) {
                $need_modify[$one_com['ma_id']] = 1;
            }

            if (in_array($one_com['mm_id'], $medicine_missing_ids)) {
                $need_find[$one_com['ma_id']] = 1;
            }
        }

        $anagraph_source_data = AnagraphSourceRelation::whereIn('ma_id', $ma_ids)->get();
        $anagraph_source_data = $anagraph_source_data->toArray();

        $need_source = [];
        foreach ($anagraph_source_data as $one_source) {
            if (isset($one_source['ma_id']) && isset($one_source['mp_id'])) {
                $need_source[$one_source['ma_id']] = $one_source['mp_id'];
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

            if (!isset($need_source[$val->ma_id])) {
                $val->setAttribute('need_source', true);
            }

            if (isset($need_find[$val->ma_id])) {
                $val->setAttribute('need_find', true);
            }
        }

        $sources = Anagraph::where('is_del', '=', 0)
            ->groupBy('anagraph_origin')
            ->pluck('anagraph_origin');

        return view('anagraph.list', compact('posts', 'sources'));
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
        $missing_tmp = [];
        foreach ($medicines as $one_med) {
            $medicines_tmp[intval($one_med['mm_id'])] = $one_med['medicine_name'];
            $missing_tmp[intval($one_med['mm_id'])] = $one_med['is_missing'];
        }

        foreach ($composes as &$one_com) {
            $one_com['medicine_name']   = $medicines_tmp[intval($one_com['mm_id'])];
            $one_com['is_missing']      = $missing_tmp[intval($one_com['mm_id'])];

            if ($one_com['usage'] == '') {
                $usage = [];
            } else {
                $usage = json_decode($one_com['usage'], true);
            }

            $one_com['usage'] = implode(',', $usage);
        }

        $anagraphsource = Anagraph::where('is_del', '=', 0)->find($ma_id)->anagraphsource()->first();
        $anagraphsource = $anagraphsource->toArray();

        $ana_rela = AnagraphSourceRelation::where('ma_id', $ma_id)
            ->where('mp_id', $anagraphsource['mp_id'])
            ->first()
            ->toArray();

        if ($ana_rela) {
            $anagraphsource['masr_id'] = $ana_rela['masr_id'];
        }

        $anagraph = Anagraph::where('ma_id', '=', $ma_id)->where('is_del', '=', 0)->first();
        $anagraph = $anagraph->toArray();

        $anagraph['consist'] = $composes;
        $anagraph['anagraph_source'][] = $anagraphsource;

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

        $medicines = Medicament::whereIn('mm_id', $ids)->where('is_del', '=', 0)->get();
        $medicines = $medicines->toArray();

        $medicines_tmp = [];
        foreach ($medicines as $one_med) {
            $medicines_tmp[intval($one_med['mm_id'])] = $one_med['medicine_name'];
        }

        foreach ($composes as &$one_com) {
            $one_com['medicine_name'] = $medicines_tmp[intval($one_com['mm_id'])];
            $usage = json_decode($one_com['usage'], true);

            if (!$usage) {
                $usage = [];
            }

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
        $str_anagraph_name      = strval($input_data['anagraph_name']);
        $str_anagraph_origin    = strval($input_data['anagraph_origin']);
        $str_author             = strval($input_data['author']);
        $str_func               = strval($input_data['func']);
        $str_usage              = strval($input_data['usage']);
        $str_inference          = strval($input_data['inference']);
        $arr_medicines          = $input_data['medicines'];

        $obj_anagraph = Anagraph::where('ma_id', '!=', $int_ma_id)
            ->where('anagraph_name', '=', $str_anagraph_name)
            ->where('author', '=', $str_author)
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
                    'author'            => $str_author,
                    'func'              => $str_func,
                    'usage'             => $str_usage,
                    'inference'         => $str_inference
                ]);
        }

        $composes = AnagraphCompose::where('ma_id', '=', $int_ma_id)->where('is_del', '=', 0)->get();
        $composes = $composes->toArray();

        $delete_ids = [];
        foreach ($composes as $one_com) {
            $delete_ids[intval($one_com['mac_id'])] = intval($one_com['mac_id']);
        }

        foreach ($arr_medicines as $one_medicine) {
            if (is_array($one_medicine) && count($one_medicine) > 0 && strval($one_medicine['name']) != '') {
                $int_mac_id         = isset($one_medicine['mac_id'])? intval($one_medicine['mac_id']) : 0;
                $int_mm_id          = isset($one_medicine['mm_id'])? intval($one_medicine['mm_id']) : 0;
                $str_medicine_name  = strval($one_medicine['name']);
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
                    if (isset($obj_new_medicine->mm_id)) {
                        if ($int_mac_id > 0) {
                            AnagraphCompose::where('mac_id', '=', $int_mac_id)
                                ->where('is_del', '=', 0)
                                ->update([
                                    'mm_id'             => intval($obj_new_medicine->mm_id),
                                    'dosage'            => $str_dosage,
                                    'standard_dosage'   => $standard_dosage,
                                    'usage'             => $str_usage,
                                    'need_modify'       => $int_need_modify
                                ]);
                        } else {
                            // 新增药物组成
                            AnagraphCompose::create([
                                'ma_id'             => $int_ma_id,
                                'mm_id'             => intval($obj_new_medicine->mm_id),
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

        $str_anagraph_name      = strval($input_data['anagraph_name']);
        $str_anagraph_origin    = strval($input_data['anagraph_origin']);
        $str_author             = strval($input_data['author']);
        $str_func               = strval($input_data['func']);
        $str_usage              = strval($input_data['usage']);
        $str_inference          = strval($input_data['inference']);
        $arr_medicines          = $input_data['medicines'];

        $obj_anagraph = Anagraph::where('anagraph_name', '=', $str_anagraph_name)
            ->where('anagraph_origin', '=', $str_anagraph_origin)
            ->where('author', '=', $str_author)
            ->where('is_del', '=', 0)
            ->first();

        if (!empty($obj_anagraph)) {
            return '0';
        } else {
            $obj_new_anagraph = Anagraph::create([
                'anagraph_name'     => $str_anagraph_name,
                'anagraph_origin'   => $str_anagraph_origin,
                'indexs'            => '[]',
                'author'            => $str_author,
                'func'              => $str_func,
                'usage'             => $str_usage,
                'inference'         => $str_inference
            ]);
        }

        if (empty($obj_new_anagraph)) {
            return '0';
        } else {
            $int_ma_id = $obj_new_anagraph->ma_id;
        }

        foreach ($arr_medicines as $one_medicine) {
            if (strval($one_medicine['name']) != '') {
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
                    if (!isset($obj_new_medicine->mm_id)) {
                        return '0';
                    } else {
                        $int_mm_id = $obj_new_medicine->mm_id;
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

    public function deleteAnagraph(Request $request) {
        $int_ma_id = intval($request->get('ma_id'));

        if ($int_ma_id > 0) {
            // 1.删除方剂
            Anagraph::where('ma_id', '=', $int_ma_id)
                ->where('is_del', '=', 0)
                ->update([
                    'is_del' => 1
                ]);
            // 2.删除方剂组成
            AnagraphCompose::where('ma_id', '=', $int_ma_id)
                ->where('is_del', '=', 0)
                ->update([
                    'is_del' => 1
                ]);

            return '1';
        }

        return '0';
    }

    public function uploadAnagraphs(Request $request) {
        $upload_file        = $request->file('import_file');
        $anagraph_origin    = $request->get('anagraph_origin');

        if ($upload_file->isValid()) {
            $path = $upload_file->store('upload');

            $str_contents = Storage::get($path);

            $arr_anagraphs = $this->parseFile($str_contents);

            $this->saveParsedData($arr_anagraphs, $anagraph_origin);
        }

        return Redirect::to('import');
    }

    private function saveParsedData($arr_anagraphs, $anagraph_origin) {
        foreach ($arr_anagraphs as $one_anagraph) {
            if (isset($one_anagraph['consist'])) {
                $str_anagraph = $one_anagraph['anagraph_name'];

                $arr_ana_res = Anagraph::where('anagraph_name', '=', $str_anagraph)
                                ->where('anagraph_origin', '=', $anagraph_origin)
                                ->where('is_del', '=', 0)
                                ->first();

                if ($arr_ana_res && isset($arr_ana_res->anagraph_name)) {
                    $arr_uni_ana_res = AnagraphSourceRelation::where('mp_id', '=', $one_anagraph['mp_id'])
                                    ->where('is_del', '=', 0)
                                    ->first();

                    if ($arr_uni_ana_res) {
                        $arr_uni_ana_res = $arr_uni_ana_res->toArray();

                        Anagraph::where('ma_id', '=', $arr_uni_ana_res['ma_id'])
                            ->where('is_del', '=', 0)
                            ->update([
                                'modify_time' => date('Y-m-d H:i:s', time())
                            ]);

                        $int_ma_id = intval($arr_uni_ana_res['ma_id']);
                    } else {
                        $obj_create_ana = Anagraph::create([
                            'anagraph_name'     => $one_anagraph['anagraph_name'] . '-' . $one_anagraph['mp_id'],
                            'anagraph_origin'   => $anagraph_origin,
                            'indexs'            => json_encode($one_anagraph['indexs']),
                            'create_time'       => date('Y-m-d H:i:s', time()),
                            'modify_time'       => date('Y-m-d H:i:s', time())
                        ]);

                        $int_ma_id = intval($obj_create_ana->ma_id);
                    }
                } else {
                    // 插入药方数据
                    $obj_create_ana = Anagraph::create([
                        'anagraph_name'     => $one_anagraph['anagraph_name'],
                        'anagraph_origin'   => $anagraph_origin,
                        'indexs'            => json_encode($one_anagraph['indexs']),
                        'create_time'       => date('Y-m-d H:i:s', time()),
                        'modify_time'       => date('Y-m-d H:i:s', time())
                    ]);

                    $int_ma_id = intval($obj_create_ana->ma_id);
                }

                $anagraph_source_data = AnagraphSourceRelation::where('ma_id', $int_ma_id)->first();
                if (!$anagraph_source_data) {
                    AnagraphSourceRelation::create([
                        'ma_id' => $int_ma_id,
                        'mp_id' => $one_anagraph['mp_id']
                    ]);
                }

                foreach ($one_anagraph['consist'] as $one_data) {
                    if (!isset($one_data['medicine_name'])) {
                        continue;
                    }

                    $str_medicine = $one_data['medicine_name'];

                    $arr_med_res = Medicament::where('medicine_name', '=', $str_medicine)
                        ->where('is_del', '=', 0)
                        ->first();

                    if ($arr_med_res && isset($arr_med_res->medicine_name)) {
                        $int_mm_id = intval($arr_med_res->mm_id);
                    } else {
                        // 插入药剂数据
                        $obj_create_med = Medicament::create(array(
                            'medicine_name' => $str_medicine,
                            'create_time'   => date('Y-m-d H:i:s', time()),
                            'modify_time'   => date('Y-m-d H:i:s', time())
                        ));

                        $int_mm_id = $obj_create_med->mm_id;
                    }

                    $arr_com_res = AnagraphCompose::where('ma_id', '=', $int_ma_id)
                        ->where('mm_id', '=', $int_mm_id)
                        ->where('is_del', '=', 0)
                        ->first();

                    if ($arr_com_res && isset($arr_com_res->ma_id)) {
                        $arr_com_res = $arr_com_res->toArray();
                        AnagraphCompose::where('mac_id', '=', $arr_com_res['mac_id'])
                            ->where('is_del', '=', 0)
                            ->update([
                                'ma_id'             => $int_ma_id,
                                'mm_id'             => $int_mm_id,
                                'dosage'            => isset($one_data['medicine_dosage']) ? $one_data['medicine_dosage']:'',
                                'usage'             => isset($one_data['usage']) ? json_encode($one_data['usage']) : '',
                                'create_time'       => date('Y-m-d H:i:s', time()),
                                'modify_time'       => date('Y-m-d H:i:s', time()),
                                'need_modify'       => isset($one_data['error']) ? 1 : 0
                            ]);
                    } else {
                        AnagraphCompose::create(array(
                            'ma_id'             => $int_ma_id,
                            'mm_id'             => $int_mm_id,
                            'dosage'            => isset($one_data['medicine_dosage']) ? $one_data['medicine_dosage']:'',
                            'usage'             => isset($one_data['usage']) ? json_encode($one_data['usage']) : '',
                            'create_time'       => date('Y-m-d H:i:s', time()),
                            'modify_time'       => date('Y-m-d H:i:s', time()),
                            'need_modify'       => isset($one_data['error']) ? 1 : 0
                        ));
                    }
                }
            }
        }
    }

    private function parseFile(string $str_content) {
        $arr_content = explode("\n", $str_content);

        $arr_anagraphs = [];

        $cousor = 0;
        $line_flag = false;
        foreach ($arr_content as $str_line) {
        	if (!empty($str_line)) {
        		$str_line = trim($str_line);

        		if (preg_match_all('/^([\x{4e00}-\x{9fa5}]*)：(\S+)([\x{4e00}-\x{9fa5}]*)/isu', $str_line, $matches) &&
        			isset($matches[1][0]) &&
        			isset($matches[2][0])
        		) {

        			if ($matches[1][0] == '名称') {
        				$tmp_ana = [];
        				$tmp_ana['anagraph_name'] = trim($matches[2][0]);

        				$cousor += 1;
        				$line_flag = 1;
        			}

        			if ($matches[1][0] == '组成') {
        				$tmp_ana['consist'] = $this->getConsist(trim($matches[2][0]));

        				$line_flag = 2;
        			}

                    if ($matches[1][0] == '来源') {
        				$tmp_ana['origin'] = trim($matches[2][0]);

        				$line_flag = 3;
        			}

                    if ($matches[1][0] == '编号') {
        				$tmp_ana['mp_id'] = intval($matches[2][0]);

        				$line_flag = 4;
        			}
        		}

        		if ($line_flag == 4) {
        			$tmp_ana['indexs'] = [];
        			$arr_anagraphs[] = $tmp_ana;

        			$tmp_ana = [];
        			$line_flag = 0;
        		}
        	}
        }

        return $arr_anagraphs;
    }

    private function getConsist($str_consist) {
    	$split_arr = [
    		1, 2, 3, 4, 5, 6, 7, 8, 9, '半', '百'
    	];

    	$std_consist = preg_replace('/（.*?）/', '', $str_consist);
    	// var_dump([$tmp_consist, $str_consist]);exit;

    	$tmp_consist = strstr($std_consist, '各等分', true);
    	if ($tmp_consist) {
    		$arr_consist = explode('、', $tmp_consist);
    	} else {
    		$arr_consist = explode('，', $std_consist);
    	}

    	$return_consist = [];

    	foreach ($arr_consist as $item_con) {
    		$item_con = str_replace("。", '', $item_con);

    		$tmp_medicine = [];
    		foreach ($split_arr as $item_split) {
    			$tmp_name = strstr($item_con, strval($item_split), true);
    			$tmp_dosage = strstr($item_con, strval($item_split));

    			if ($tmp_name) {
    				$tmp_medicine['medicine_name'] = $tmp_name;
    				if ($tmp_dosage) {
    					$tmp_medicine['medicine_dosage'] = $tmp_dosage;
    				} else {
    					$tmp_medicine['medicine_dosage'] = '';
    				}
    				break;
    			}
    		}

            if (isset($tmp_medicine['medicine_name'])) {
                $str_consist = str_replace($tmp_medicine['medicine_name'], '', $str_consist);
            }

            if (isset($tmp_medicine['medicine_dosage'])) {
                $str_consist = str_replace($tmp_medicine['medicine_dosage'], '', $str_consist);
            }

    		if (isset($tmp_medicine['medicine_name'])) {
    			$return_consist[] = $tmp_medicine;
    		} else {
    			$tmp_medicine['medicine_name'] = $item_con;
    			$tmp_medicine['medicine_dosage'] = '';
    			$tmp_medicine['error'] = true;

    			$return_consist[] = $tmp_medicine;
    		}
    	}

    	$arr_usage = [];
    	$tmp_arr_usage = explode('，（', $str_consist);
    	foreach ($tmp_arr_usage as $one_tmp_usage) {
    		$tmp_usage = strstr($one_tmp_usage, '）');
    		if (!$tmp_usage) {
    			$arr_usage[] = $one_tmp_usage;
    		} else {
    			$arr_tmp_usage_exp = explode('，', $tmp_usage);
    			if (count($arr_tmp_usage_exp) > 1) {
    				foreach ($arr_tmp_usage_exp as $one_exp) {
    					if ($one_exp == '）') {
    						$arr_usage[] = $one_tmp_usage;
    					} else {
    						$arr_usage[] = "";
    					}
    				}
    			} else {
    				$arr_usage[] = $one_tmp_usage;
    			}
    		}
    	}

    	foreach ($arr_usage as $index => $item_usage) {
    		$str_replace 	= strstr($item_usage, '）');
    		$item_usage 	= str_replace($str_replace, '', $item_usage);
    		$item_usage 	= str_replace('。', '', $item_usage);

    		$return_consist[$index]['usage'] = [$item_usage];
    	}

    	return $return_consist;
    }
}
