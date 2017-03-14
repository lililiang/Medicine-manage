<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $fillable = [
        'disease_name',
        'disease_desc',
    ];
}