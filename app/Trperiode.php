<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trperiode extends Model
{
    protected $table = "trtransaksiperiode";
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
