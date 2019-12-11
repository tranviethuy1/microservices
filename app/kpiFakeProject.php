<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class kpiFakeProject extends Model
{
    protected $fillable = [
        'reality'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    public $table = "kpi_fake_tables";

    public $timestamps = false;
}
