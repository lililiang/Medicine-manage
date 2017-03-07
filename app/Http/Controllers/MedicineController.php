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
        $posts = Medicament::where('is_del', '=', 0)
        ->orderBy('mm_id')
        ->paginate(config('medicine.posts_per_page'));

        return view('medicine.list', compact('posts'));
    }
}
