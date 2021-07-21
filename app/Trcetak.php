<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trcetak extends Model
{
    protected $table = "trcetak";
    protected $primaryKey = "KodeBarang";
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $guarded = [];

}
