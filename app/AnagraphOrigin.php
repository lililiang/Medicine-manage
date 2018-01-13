<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphOrigin extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey   = 'mao_id';
    protected $table        = 'anagraph_origins';

    protected $fillable = [
        'book_name',
        'book_intro'
    ];
}
