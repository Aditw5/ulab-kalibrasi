<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPemeriksaanImunohistokimia extends _BaseModel
{
    use HasFactory;

    protected $table = "hasilpemeriksaanimunohistokimia_t";
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
