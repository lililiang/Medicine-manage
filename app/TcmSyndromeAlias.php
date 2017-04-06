<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TcmSyndromeAlias extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';

    protected $primaryKey = 'mtsa_id';

    protected $fillable = [
        'syndrome_alias',
    ];

    public function syndrome()
    {
        return $this->belongsToMany('App\TcmSyndrome', 'tcm_syndrome_alias_relations', 'mtsa_id', 'mts_id');
    }
}
