<?php

namespace App\Models\Standar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjekModulAplikasi extends Model
{
    use HasFactory;
    
    protected $table = "objekmodulaplikasi_s";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "id";


}
