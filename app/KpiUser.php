<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KpiUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'id_user', 'complete_tasks', 'rate1', 'working_hours', 'rate2', 'time_late'
        , 'rate3', 'month', 'year'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $table = "kpi_users";

    public $timestamps = false;
}
