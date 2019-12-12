<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiUpdate extends Model
{
    protected $fillable = [
        'project_id', 'data'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public $table = "kpi_project_update";

    public $timestamps = false;
}
