<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Anagraph;
use App\AnagraphCompose;
use App\Medicament;
use App\MedicineDataSource;
use App\MedicamentSourceRelation;

class MedicineController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $posts = Medicament::where('is_del', '=', 0)
            ->orderBy('mm_id')
            ->paginate(config('medicine.posts_per_page'));

        $med_data = $posts->toArray();
        if (isset($med_data['data'])) {
            $med_data = $med_data['data'];

            $mm_ids = [];

            foreach ($med_data as $one_data) {
                $mm_ids[] = intval($one_data['mm_id']);
            }

            $med_source_relas = MedicamentSourceRelation::whereIn('mm_id', $mm_ids)->get();
            $med_source_relas = $med_source_relas->toArray();

            $mmds_ids       = [];
            $med_source_ids = [];
            foreach ($med_source_relas as $one_med_rela) {
                $med_source_ids[$one_med_rela['mm_id']] = $one_med_rela['mmds_id'];
                $mmds_ids[$one_med_rela['mmds_id']] = 1;
            }

            $mmds_ids = array_keys($mmds_ids);

            $source_data = MedicineDataSource::whereIn('mmds_id', $mmds_ids)->get();
            $source_data = $source_data->toArray();

            $med_sources  = [];
            foreach ($source_data as $one_source) {
                $med_sources[$one_source['mmds_id']] = $one_source['name'];
            }

            $med_source_result_data = [];
            foreach ($med_source_ids as $mm_id => $mmds_id) {
                if (isset($med_sources[$mmds_id])) {
                    $med_source_result_data[$mm_id] = [
                        'mmds_id'   => $mmds_id,
                        'name'      => $med_sources[$mmds_id]
                    ];
                }
            }
        }

        // add syndrome data
        foreach ($posts->getIterator() as $val) {
            if (isset($med_source_result_data[$val->mm_id])) {
                $val->setAttribute('mmds_id', $med_source_result_data[$val->mm_id]['mmds_id']);
                $val->setAttribute('standard_name', $med_source_result_data[$val->mm_id]['name']);
            }
        }

        return view('medicine.list', compact('posts'));
    }

    public function showMedicine($mm_id) {
        $medsource = Medicament::where('is_del', '=', 0)->find($mm_id)->medicinesource()->get();
        $medsource = $medsource->toArray();

        $medicine = Medicament::where('mm_id', '=', $mm_id)->where('is_del', '=', 0)->first();
        $medicine = $medicine->toArray();

        if (!empty($medsource)) {
            $medicine['source'] = $medsource[0];
        }

        $obj_ana_com = new AnagraphCompose();
        $ana_data = $obj_ana_com->getRelatedAnagraph($mm_id);

        if (!empty($ana_data)) {
            $medicine['related_anagraphs'] = $ana_data;
        }

        $disease_count = Medicament::where('mm_id', '<=', $mm_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($disease_count / intval(config('medicine.posts_per_page')));
        $medicine['page_index'] = $page_index;

        return view('medicine.detail', compact('medicine'));
    }

    public function delteMedicineRelation(Request $request) {
        $mm_id = $request->get('mm_id');

        $del_res = MedicamentSourceRelation::where('mm_id', '=', $mm_id)->delete();
        if ($del_res) {
            return '1';
        } else {
            return '0';
        }
    }
}
