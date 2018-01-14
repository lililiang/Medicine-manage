<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnagraphCollectionsController extends Controller
{
    //
    public function list() {
        return view('anagraphcollections.list');
    }
}
