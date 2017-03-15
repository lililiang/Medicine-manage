<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TcmSyndromeDiseaseRelation extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';
    
    public $timestamps = false;

    protected $fillable = [
        'mts_id',
        'md_id',
    ];
}
