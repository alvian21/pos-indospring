<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mskategori extends Model
{
    protected $table = "mskategori";
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
