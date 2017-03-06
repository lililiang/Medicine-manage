<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Anagraph;
use App\AnagraphCompose;
use App\Medicament;

class MedicineController extends Controller
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
        ->paginate(config('blog.posts_per_page'));

        return view('medicine.list', compact('posts'));
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

        return view('medicine.detail', compact('anagraph'));
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

        return view('medicine.edit', compact('anagraph'));
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
            $delete_ids[intval($one_com['mm_id'])] = intval($one_com['mm_id']);
        }

        foreach ($arr_medicines as $one_medicine) {
            $int_mac_id         = isset($one_medicine['mac_id'])? intval($one_medicine['mac_id']) : 0;
            $int_mm_id          = isset($one_medicine['mm_id'])? intval($one_medicine['mm_id']) : 0;
            $str_medicine_name  = $one_medicine['name'];
            $str_dosage         = isset($one_medicine['dosage'])? $one_medicine['dosage'] : '';
            $tmp_usage          = explode(',', strval($one_medicine['usage']));
            $str_usage          = json_encode($tmp_usage);

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
                                'mm_id'     => intval($obj_new_medicine->id),
                                'dosage'    => $str_dosage,
                                'usage'     => $str_usage,
                            ]);
                    } else {
                        // 新增药物组成
                        AnagraphCompose::create([
                            'ma_id'     => $int_ma_id,
                            'mm_id'     => intval($obj_new_medicine->id),
                            'dosage'    => $str_dosage,
                            'usage'     => $str_usage,
                        ]);
                    }
                } else {
                    return '0';
                }
            } else {
                $arr_medicine = $obj_medicine->toArray();
                if ($int_mm_id != $arr_medicine['mm_id']) {
                    AnagraphCompose::where('mac_id', '=', $int_mac_id)
                        ->where('is_del', '=', 0)
                        ->update([
                            'mm_id'     => $arr_medicine['mm_id'],
                            'dosage'    => $str_dosage,
                            'usage'     => $str_usage,
                        ]);
                } else {
                    AnagraphCompose::where('mac_id', '=', $int_mac_id)
                        ->where('is_del', '=', 0)
                        ->update([
                            'dosage'    => $str_dosage,
                            'usage'     => $str_usage,
                        ]);
                }
            }

            if ($int_mm_id > 0) {
                unset($delete_ids[$int_mm_id]);
            }
        }
        // 软删数据
        AnagraphCompose::where('ma_id', '=', $int_ma_id)
            ->whereIn('mm_id', array_keys($delete_ids))
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
            $str_dosage         = isset($one_medicine['dosage'])? $one_medicine['dosage'] : '';
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
                'ma_id'     => $int_ma_id,
                'mm_id'     => $int_mm_id,
                'dosage'    => $str_dosage,
                'usage'     => $str_usage,
            ]);

            if (empty($obj_res)) {
                return '0';
            }
        }

        return '1';
    }
}
