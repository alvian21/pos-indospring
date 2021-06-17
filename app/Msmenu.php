<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msmenu extends Model
{
    protected $table = "msmenu";
    protected $primaryKey = "ItemIndex";
    public $timestamps = false;
}
