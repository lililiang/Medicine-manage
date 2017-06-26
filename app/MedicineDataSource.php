<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicineDataSource extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey = 'mmds_id';

    protected $fillable = [
        'alias',
        'name',
        'direct',
        'func',
        'prop'
    ];
}
