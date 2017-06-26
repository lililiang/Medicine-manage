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
    ];

    public function anagraphsource()
    {
        return $this->belongsToMany('App\PrescriptionDataSource', 'anagraph_source_relations', 'ma_id', 'mp_id');
    }
}
