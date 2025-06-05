<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalBedah extends _BaseModel
{
    use HasFactory;
    protected $table = "jadwalbedah_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

   
}
