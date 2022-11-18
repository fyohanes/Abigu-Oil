<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class selledAssets extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }
    public function asset(){
        return $this->belongsTo(Asset::class,'asset_id');

    }
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id');
    }
}