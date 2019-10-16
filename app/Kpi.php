<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'position', 'kpi'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $table = "kpis";

    public $timestamps = false;
}
