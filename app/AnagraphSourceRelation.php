<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphSourceRelation extends Model
{
    //
    public $timestamps = false;

    protected $primaryKey = 'masr_id';

    protected $fillable = [
        'ma_id',
        'mp_id',
    ];
}
