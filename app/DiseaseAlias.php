<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiseaseAlias extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey = 'mda_id';

    protected $fillable = [
        'disease_alias',
    ];

    public function disease()
    {
        return $this->belongsToMany('App\Disease', 'diseases', 'mda_id', 'md_id');
    }
}
