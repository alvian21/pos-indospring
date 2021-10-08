<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trhpp extends Model
{
    protected $table = "trhpp";
    protected $fillable = ["Periode","KodeBarang","KodeLokasi","Hpp"];
    public $timestamps = false;

    protected $casts = [
        'KodeBarang' => 'string',
    ];
}
