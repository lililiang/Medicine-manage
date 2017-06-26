<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    //
    const UPDATED_AT = 'modify_time';
    const CREATED_AT = 'create_time';

    protected $primaryKey = 'mm_id';

    protected $fillable = [
        'medicine_name'
    ];

    public function medicinesource()
    {
        return $this->belongsToMany('App\MedicineDataSource', 'medicament_source_relations', 'mm_id', 'mmds_id');
    }
}
