<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anagraph extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey = 'ma_id';

    protected $fillable = [
        'anagraph_name',
        'anagraph_origin',
        'indexs',
        'author',
        'func',
        'usage',
        'inference'
    ];

    public function anagraphsource()
    {
        return $this->belongsToMany('App\PrescriptionDataSource', 'anagraph_source_relations', 'ma_id', 'mp_id');
    }

    public function getPrevNext($ma_id) {
        $prev = $this->where('ma_id', '<', $ma_id)
            ->where('is_del', '=', 0)
            ->orderBy('ma_id', 'desc')
            ->first();

        $next = $this->where('ma_id', '>', $ma_id)
            ->where('is_del', '=', 0)
            ->orderBy('ma_id', 'asc')
            ->first();

        $prev_next = [];
        if ($prev) {
            $prev = $prev->toArray();
            $prev_next['prev'] = $prev['ma_id'];
        } else {
            $prev_next['prev'] = false;
        }

        if ($next) {
            $next = $next->toArray();
            $prev_next['next'] = $next['ma_id'];
        } else {
            $prev_next['next'] = false;
        }

        return $prev_next;
    }
}
