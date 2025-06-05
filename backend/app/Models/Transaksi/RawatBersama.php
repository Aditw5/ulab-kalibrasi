<?php

namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class RawatBersama extends _BaseModel
{
    use HasFactory;
    protected $table = "rawatbersama_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";
}
