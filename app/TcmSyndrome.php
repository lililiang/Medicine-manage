<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TcmSyndrome extends Model
{
    //
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'modify_time';

    protected $primaryKey = 'mts_id';

    protected $fillable = [
        'syndrome_name',
        'syndrome_desc',
    ];

    public function disease()
    {
        return $this->belongsToMany('App\Disease', 'tcm_syndrome_disease_relations', 'mts_id', 'md_id');
    }

    public function alias() {
        return $this->belongsToMany('App\TcmSyndromeAlias', 'tcm_syndrome_alias_relations', 'mts_id', 'mtsa_id');
    }
}
