<?php

namespace App\Models\Master;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipePegawai extends Model
{
    protected $table = "typepegawai_m";
    protected $guarded = [];
    public $timestamps = true;
    public $incrementing = false;
    protected $primaryKey = "id";
    public function scopeMine(Builder $builder, int $kdProfile = null)
    {
        if(!empty(request()->session()->get('kdProfile'))) {
            $kdProfile = request()->session()->get('kdProfile');
        }else{
            $kdProfile = Profile::where('statusenabled',true)->first()->id;
        }
        return $builder->where("kdprofile", $kdProfile)
        ->where('statusenabled',true)
        ->select('id','typepegawai');
    }
    
    public function scopeSearch(Builder $builder, string $search = null)
    {
        if (empty($search)) {
            return $builder;
        }

        return $builder->where(function (Builder $sql) use ($search) {
            $sql->where("typepegawai", "like", "%{$search}%")
                ->orWhere("reportdisplay", "like", "%{$search}%");
        });
    }

}