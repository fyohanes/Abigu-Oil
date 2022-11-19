<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function seller(){
        return $this->belongsTo(User::class,'requested_by')->withTrashed();
    }
    public function admin(){
        return $this->belongsTo(User::class,'approved_by')->withTrashed();
    }
    public function asset(){
        return $this->belongsTo(Asset::class,'asset_id');
    }
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id');
    }
}
