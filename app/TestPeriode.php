<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestPeriode extends Model
{
    protected $table = "test_periode";
    public $timestamps = false;

    protected $fillable = [
        'Nomor',
        'Periode',
        'KodeUser',
        'KodeTransaksi',
        'Nilai',
        'UserUpdate',
        'LastUpdate'
    ];
}
