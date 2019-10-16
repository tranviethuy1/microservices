<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiDepartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_department', 'profits', 'month', 'year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $table = "kpi_department";

    public $timestamps = false;
}
