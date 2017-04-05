<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey = 'md_id';

    protected $fillable = [
        'disease_name',
        'disease_desc',
    ];

    public function tcmSyndromes()
    {
        return $this->belongsToMany('App\TcmSyndrome', 'tcm_syndrome_disease_relations', 'md_id', 'mts_id');
    }

    public function alias()
    {
        return $this->belongsToMany('App\DiseaseAlias', 'disease_alias_relations', 'md_id', 'mda_id');
    }
}
