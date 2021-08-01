<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msbarang extends Model
{
    protected $table = "msbarang";
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'HargaJual' => 'integer',
        'TampilDiMobile' => 'integer'
    ];

}
