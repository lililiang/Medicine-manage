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
        return $this->belongsToMany('App\Disease', 'diseases', 'mts_id', 'md_id');
    }
}
