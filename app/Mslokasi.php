<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mslokasi extends Model
{
    protected $table = "mslokasi";
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
