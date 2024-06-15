<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stuff extends Model
{
    use SoftDeletes;
    //menentukan colom wajib diisi atau tidak
    protected $fillable = ["name","category"]; 

    public function stuffstock(){
        return $this->hasOne(StuffStock::class);
    } 

    public function inboundstuffs(){
        return $this->hasMany(InboundStuff::class);
    }

    public function lendings(){
        return $this->hasMany(Lending::class);
    }
}
