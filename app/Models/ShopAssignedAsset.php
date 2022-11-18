<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopAssignedAsset extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assets(){
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function shops(){
        return $this->belongsTo(Shop::class,'shop_id');
    }
    public function wareHouse(){
        return $this->belongsTo(WareHouse::class,'ware_house_id');
    }
}
