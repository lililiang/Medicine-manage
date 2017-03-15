<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TcmSyndromeAliasRelation extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';
    
    public $timestamps = false;

    protected $fillable = [
        'mts_id',
        'mtsa_id',
    ];
}
