<?php
/**
 * Created by PhpStorm.
 * User: agus.sustian@epic01
 * Date: 09/08/2017
 * Time: 20.43
 */
namespace App\Models\Transaksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokProdukKadaluarsa extends _BaseModel
{
    protected $table ="stokprodukkadaluarsa_t";
    protected $primaryKey = 'norec';
    protected $fillable = [];
    public $timestamps = false;
    public $incrementing = false;


//    public function ruangan(){
//        return $this->belongsTo('App\Master\Ruangan', 'objectruanganfk');
//    }
//
//    public function produk(){
//        return $this->belongsTo('App\Master\Produk', 'objectruanganfk');
//    }

}
