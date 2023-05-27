<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderedMenu extends Model
{
    use HasFactory;

    protected $table = "ordered_menus";
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
