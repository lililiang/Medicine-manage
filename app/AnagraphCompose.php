<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Anagraph;

class AnagraphCompose extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $fillable = [
        'ma_id',
        'mm_id',
        'dosage',
        'standard_dosage',
        'usage',
    ];

    public function getRelatedAnagraph($mm_id) {
        $ana_coms = $this->where('is_del', '=', 0)
            ->where('mm_id', '=', $mm_id)
            ->get();

        $ana_coms = $ana_coms->toArray();

        if (empty($ana_coms)) {
            return [];
        }

        $ma_ids = [];
        foreach ($ana_coms as $one_com) {
            if (isset($one_com['ma_id']) && !in_array($one_com['ma_id'], $ma_ids)) {
                $ma_ids[] = $one_com['ma_id'];
            }
        }

        $ana_data = Anagraph::where('is_del', '=', 0)
            ->whereIn('ma_id', $ma_ids)
            ->orderBy('ma_id')
            ->get();

        $ana_data = $ana_data->toArray();

        return $ana_data;
    }
}
