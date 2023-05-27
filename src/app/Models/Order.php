<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:d F Y H:i:s',
    ];

    public function orderedMenus()
    {
        return $this->hasMany(OrderedMenu::class, 'order_id', 'id');
    }
}
