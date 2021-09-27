<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trmutasidt extends Model
{
    protected $table = "trmutasidt";
    public $timestamps = false;
    public function trmutasihd()
    {
        return $this->belongsTo('App\Trmutasihd');
    }

    protected $casts = [
        'Tanggal' => 'datetime:Y-m-d H:i:s',
    ];
}
