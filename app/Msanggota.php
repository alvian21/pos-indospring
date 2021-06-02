<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Msanggota extends Model
{
    protected $table = "msanggota";
    public $timestamps = false;
    protected $primaryKey = "Kode";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'UserPassword', 'verified_2fa', 'email', 'token', 'two_factor_code',
        'two_factor_expires_at', 'token_email', 'temporary_email', 'verified_email', 'verified_email_expires_at'
    ];

    protected $dates = ['two_factor_expires_at', 'verified_email_expires_at', 'verified_email_date'];
}
