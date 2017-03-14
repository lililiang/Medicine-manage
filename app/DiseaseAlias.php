<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiseaseAlias extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $fillable = [
        'md_id',
        'disease_alias',
    ];
}