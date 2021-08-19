<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mstransaksi extends Model
{
    protected $table = "mstransaksi";
    public $timestamps = false;
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';
}
