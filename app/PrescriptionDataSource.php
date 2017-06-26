<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDataSource extends Model
{
    //
    protected $primaryKey = 'mp_id';

    protected $table = 'prescription_data_source';
}
