<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrescriptionDataSource;

class PrescriptionDataSourceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function list()
    {
        $posts = PrescriptionDataSource::where('is_del', '=', 0)
            ->orderBy('mp_id')
            ->paginate(config('medicine.posts_per_page'));

        return view('prescriptiondatasource.list', compact('posts'));
    }

    public function detail($mp_id) {
        $prescription = PrescriptionDataSource::where('is_del', '=', 0)
            ->where('mp_id', '=', $mp_id)
            ->first();

        $prescription = $prescription -> toArray();

        $prescription_count = PrescriptionDataSource::where('mp_id', '<=', $mp_id)->where('is_del', '=', 0)->count();
        $page_index = ceil($prescription_count / intval(config('medicine.posts_per_page')));
        $prescription['page_index'] = $page_index;

        return view('prescriptiondatasource.detail', compact('prescription'));
    }
}
