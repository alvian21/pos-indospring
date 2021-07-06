<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mssupplier extends Model
{
    protected $table = "mssupplier";
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}
