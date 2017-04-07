<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphSimilarity extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    public $timestamps = false;

    protected $primaryKey = 'mas_id';

    protected $fillable = [
        'src_id',
        'des_id',
        'similarity',
    ];
}
