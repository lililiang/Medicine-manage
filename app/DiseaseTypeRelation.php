<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiseaseTypeRelation extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';

    public $timestamps = false;

    protected $fillable = [
        'mdt_id',
        'md_id',
    ];
}
