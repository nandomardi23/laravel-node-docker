<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'status',
        'desc'
    ];

    public function categories() {
        return $this->belongsTo(Categories::class,'category_id','id');
    }

    // new
    public function category() {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
