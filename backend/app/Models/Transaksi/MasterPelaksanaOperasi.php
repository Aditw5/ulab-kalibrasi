<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPelaksanaOperasi extends _BaseModel
{
    use HasFactory;
    protected $table = "masterpelaksanabedah_m";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "id";
   
}
