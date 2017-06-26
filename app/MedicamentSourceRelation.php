<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicamentSourceRelation extends Model
{
    //
    public $timestamps = false;

    protected $fillable = [
        'mm_id',
        'mmds_id',
    ];
}
