<?php
namespace App\Models\Transaksi;


class EMRBSRE extends _BaseModel
{
    protected $table = "emr_bsre_t";
    protected $fillable = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "norec";

}