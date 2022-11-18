<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedUser extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'assigned_user';
    public function shop(){
        return $this->belongsTo(Shop::class,'shop_id');
    }
}
