<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $table = 'tag';
    protected $primaryKey = 'mt_id';

    protected $fillable = [
        'name',
        'desc',
        'type'
    ];

    public function anagraphs() {
        return $this->belongsToMany('App\Anagraph', 'anagraph_tag', 'mt_id', 'mat_id');
    }

    public function getTagsByType(string $type = '') {
        if (empty($type)) {
            $tags = $this->where('is_del', '=', 0)->get();
        } elseif (in_array($type, ['anagraph', 'medicine', 'disease'])) {
            $tags = $this->where('is_del', '=', 0)->where('type', '=', $type)->get();
        } else {
            $tags = false;
        }

        if ($tags) {
            $tags = $tags->toArray();
        } else {
            $tags = [];
        }
        return $tags;
    }
}
