<?php

namespace App\Models\Transaksi;


class PelayananPasienObatPulang extends _BaseModel
{
    protected $table = "pelayananpasienobatpulang_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}