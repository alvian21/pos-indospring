<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traktifasi extends Model
{
    protected $table = "traktifasi";
    protected $primaryKey = "Nomor";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

}
