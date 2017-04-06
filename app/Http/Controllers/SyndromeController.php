<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TcmSyndrome;

class SyndromeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        $posts = TcmSyndrome::where('is_del', '=', 0)
            ->orderBy('mts_id')
            ->paginate(config('medicine.posts_per_page'));

        return view('syndrome.list', compact('posts'));
    }

    public function deleteSyndrome(Request $request)
    {
        $int_mts_id = intval($request->get('mts_id'));

        if ($int_mts_id > 0) {
            // 1. 去掉关联关系
            $obj_syndrome = TcmSyndrome::where('mts_id', '=', $int_mts_id)
                ->where('is_del', '=', 0)
                ->first();
            // 去掉证候别名的关联关系
            $obj_syndrome->alias()->detach($int_mts_id);
            // 去掉症状和证候的关联关系
            $obj_syndrome->disease()->detach($int_mts_id);

            // 2.软删除
            TcmSyndrome::where('mts_id', '=', $int_mts_id)
                ->where('is_del', '=', 0)
                ->update([
                    'is_del' => 1
                ]);

            return '1';
        }

        return '0';
    }
}
