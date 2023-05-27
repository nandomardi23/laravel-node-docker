<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'name',
        'typeincome_id',
        'price',
        'desc',
    ];

    public function typeincome(){
        return $this->belongsTo(TypeIncome::class);
    }
}
