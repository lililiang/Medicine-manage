<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MedicineDataSource;
use App\MedicamentSourceRelation;

class MedicineDataSourceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $posts = MedicineDataSource::where('is_del', '=', 0)
            ->orderBy('mmds_id')
            ->paginate(config('medicine.posts_per_page'));

        return view('medicinedatasource.list', compact('posts'));
    }

    public function detail($mmds_id) {
        $medicine = MedicineDataSource::where('is_del', '=', 0)
            ->where('mmds_id', '=', $mmds_id)
            ->first();

        $medicine = $medicine -> toArray();

        $medicine_count = MedicineDataSource::where('mmds_id', '<=', $mmds_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($medicine_count / intval(config('medicine.posts_per_page')));
        $medicine['page_index'] = $page_index;

        return view('medicinedatasource.detail', compact('medicine'));
    }
}
