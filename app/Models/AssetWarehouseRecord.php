<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetWarehouseRecord extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'asset_warehouse_record';

    public function asset(){
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function wareHouse(){
        return $this->belongsTo(WareHouse::class,'warehouse_id');
    }
}