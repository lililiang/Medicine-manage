<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphCompose extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $fillable = [
        'ma_id',
        'mm_id',
        'dosage',
        'standard_dosage',
        'usage',
    ];
}
