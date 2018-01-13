<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphCollections extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey   = 'mac_id';
    protected $table        = 'anagraph_collections';

    protected $fillable = [
        'author_name',
        'maa_id',
        'mao_id'
    ];
}
