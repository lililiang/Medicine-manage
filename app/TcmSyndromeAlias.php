<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TcmSyndromeAlias extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';

    protected $fillable = [
        'md_id',
        'mda_id',
    ];
}
