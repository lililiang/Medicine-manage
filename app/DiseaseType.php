<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiseaseType extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';

    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'type_desc'
    ];
}
