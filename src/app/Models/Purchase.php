<?php

namespace App\Models;

use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name_item',
        'quantity',
        'price',
        'desc',
        'photo_invoice',
        'user_id'
    ];

    // protected $casts = [
    //     'date' => 'datetime:d F Y',
    // ];

    public function getPhotoInvoiceAttribute()
    {
        $path = $this->attributes['photo_invoice'];

        if ($path != null) {
            return URL::to(Storage::url($path));
        } else {
            return null;
        }
        
    }

    public function getPhotoInvoiceRawAttribute()
    {
        $path = $this->attributes['photo_invoice'];

        return $path;
    }
}
