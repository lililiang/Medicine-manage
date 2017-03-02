<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Anagraph;
use App\AnagraphCompose;
use App\Medicament;

class MedicineController extends Controller
{
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
}
