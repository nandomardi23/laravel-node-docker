<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeIncome extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function income() {
        return $this->hasMany(Income::class);
    }
}
