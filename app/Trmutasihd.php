<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trmutasihd extends Model
{
    protected $table = "trmutasihd";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "Nomor";

    public function trmutasidt()
    {
        return $this->hasMany('App\Trmutasidt','Nomor','Nomor');
    }

    protected $casts = [
        'Tanggal' => 'datetime:Y-m-d H:i:s',
    ];
}
