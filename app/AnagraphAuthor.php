<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnagraphAuthor extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey   = 'maa_id';
    protected $table        = 'anagraph_authors';

    protected $fillable = [
        'author_name',
        'author_intro',
        'dynasty',
        'birth_year',
        'dead_year'
    ];
}
